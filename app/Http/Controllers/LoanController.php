<?php

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
    public const LOAN_DAYS    = 14;

    // ── Member: dashboard "Buku Saya" ──────────────────────────
    public function dashboard()
    {
        $user = Auth::user();
        Loan::updateOverdueStatuses();

        // Aktif = pending + borrowed + overdue
        $activeLoans = $user->loans()
            ->with('book')
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->orderByRaw("FIELD(status, 'overdue', 'borrowed', 'pending')")
            ->orderBy('due_date')
            ->get();

        $recentHistory = $user->loans()
            ->with('book')
            ->whereIn('status', ['returned', 'rejected'])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        $savedBooks = $user->wishlists()->with('book')->latest()->limit(4)->get();

        $currentlyBorrowed = $user->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->count();

        $overdueBooks = $user->loans()->where('status', 'overdue')->count();

        $dueSoon = $user->loans()
            ->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', Carbon::today()->addDays(3))
            ->count();

        return view('member.dashboard', compact(
            'activeLoans', 'recentHistory', 'savedBooks',
            'currentlyBorrowed', 'overdueBooks', 'dueSoon'
        ));
    }

    // ── Member: ajukan peminjaman ──────────────────────────────
    public function borrow(Request $request, Book $book)
    {
        $user = Auth::user();

        // Cek batas pinjam (pending + aktif)
        $activeLoanCount = $user->loans()
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->count();

        if ($activeLoanCount >= self::BORROW_LIMIT) {
            return back()->with('error', 'Kamu sudah mencapai batas peminjaman (' . self::BORROW_LIMIT . ' buku).');
        }

        // Cek ketersediaan stok
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Buku ini sedang tidak tersedia.');
        }

        // Cek sudah ada pengajuan aktif untuk buku ini
        $alreadyExists = $user->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed', 'overdue'])
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'Kamu sudah mengajukan atau meminjam buku ini.');
        }

        // Buat pengajuan — status pending, belum kurangi stok
        Loan::create([
            'record_id' => Loan::generateRecordId(),
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'status'    => 'pending',
        ]);

        return redirect()->route('member.dashboard')
            ->with('success', "Pengajuan peminjaman \"{$book->title}\" berhasil dikirim. Silakan tunggu konfirmasi dari admin.");
    }

    // ── Member: riwayat ───────────────────────────────────────
    public function history()
    {
        $user  = Auth::user();
        $loans = $user->loans()
            ->with('book')
            ->whereIn('status', ['returned', 'rejected'])
            ->latest('updated_at')
            ->paginate(10);

        return view('member.history', compact('loans'));
    }

    // ── Member: simpan ke wishlist ─────────────────────────────
    public function toggleWishlist(Book $book)
    {
        $user     = Auth::user();
        $existing = $user->wishlists()->where('book_id', $book->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Buku dihapus dari daftar simpan.');
        }

        Wishlist::create(['user_id' => $user->id, 'book_id' => $book->id]);
        return back()->with('success', 'Buku disimpan ke daftar bacaan.');
    }
}
