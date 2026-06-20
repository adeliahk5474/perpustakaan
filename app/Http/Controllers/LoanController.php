<?php
// app/Http/Controllers/LoanController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public const BORROW_LIMIT = 5;
    public const LOAN_DAYS = 14; // 2 weeks

    public function dashboard()
    {
        $user = Auth::user();
        Loan::updateOverdueStatuses();

        $activeLoans = $user->loans()
            ->with('book')
            ->whereIn('status', ['borrowed', 'overdue'])
            ->orderBy('due_date')
            ->get();

        $recentHistory = $user->loans()
            ->with('book')
            ->where('status', 'returned')
            ->latest('returned_date')
            ->limit(5)
            ->get();

        $savedBooks = $user->wishlists()->with('book')->latest()->limit(4)->get();

        $currentlyBorrowed = $activeLoans->count();
        $overdueBooks = $activeLoans->where('status', 'overdue')->count();
        $dueSoon = $activeLoans->filter(function ($loan) {
            return $loan->status !== 'overdue' && $loan->days_until_due <= 3;
        })->count();

        return view('member.dashboard', compact(
            'activeLoans',
            'recentHistory',
            'savedBooks',
            'currentlyBorrowed',
            'overdueBooks',
            'dueSoon'
        ));
    }

    public function borrow(Request $request, Book $book)
    {
        $user = Auth::user();

        // Check borrow limit
        $activeLoanCount = $user->loans()->whereIn('status', ['borrowed', 'overdue'])->count();
        if ($activeLoanCount >= self::BORROW_LIMIT) {
            return back()->with('error', "Kamu sudah mencapai batas peminjaman (" . self::BORROW_LIMIT . " buku).");
        }

        // Check availability
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Buku ini sedang tidak tersedia.');
        }

        // Check already borrowed
        $alreadyBorrowed = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Kamu sudah meminjam buku ini.');
        }

        // Create loan
        $loan = Loan::create([
            'record_id'    => Loan::generateRecordId(),
            'user_id'      => $user->id,
            'book_id'      => $book->id,
            'borrowed_date' => Carbon::today(),
            'due_date'     => Carbon::today()->addDays(self::LOAN_DAYS),
            'status'       => 'borrowed',
        ]);

        // Decrease available copies
        $book->decrement('available_copies');

        return redirect()->route('member.dashboard')
            ->with('success', "Berhasil meminjam \"{$book->title}\". Harap dikembalikan sebelum {$loan->due_date->format('d M Y')}.");
    }

    public function returnBook(Loan $loan)
    {
        $user = Auth::user();

        if ($loan->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if ($loan->status === 'returned') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $loan->update([
            'status'        => 'returned',
            'returned_date' => Carbon::today(),
        ]);

        $loan->book->increment('available_copies');

        return back()->with('success', "Buku \"{$loan->book->title}\" berhasil dikembalikan.");
    }

    public function renew(Loan $loan)
    {
        $user = Auth::user();

        if ($loan->user_id !== $user->id) {
            abort(403);
        }

        if ($loan->renewal_count >= 2) {
            return back()->with('error', 'Buku ini sudah diperpanjang maksimal 2 kali.');
        }

        if ($loan->status === 'overdue') {
            return back()->with('error', 'Buku yang sudah melewati batas waktu tidak dapat diperpanjang.');
        }

        $loan->update([
            'due_date'      => $loan->due_date->addDays(self::LOAN_DAYS),
            'is_renewed'    => true,
            'renewal_count' => $loan->renewal_count + 1,
        ]);

        return back()->with('success', "Peminjaman \"{$loan->book->title}\" berhasil diperpanjang.");
    }

    public function toggleWishlist(Book $book)
    {
        $user = Auth::user();
        $existing = $user->wishlists()->where('book_id', $book->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', "Buku dihapus dari daftar simpan.");
        }

        Wishlist::create(['user_id' => $user->id, 'book_id' => $book->id]);
        return back()->with('success', "Buku disimpan ke daftar bacaan.");
    }

    public function history()
    {
        $user = Auth::user();
        $loans = $user->loans()
            ->with('book')
            ->where('status', 'returned')
            ->latest('returned_date')
            ->paginate(10);

        return view('member.history', compact('loans'));
    }
}
