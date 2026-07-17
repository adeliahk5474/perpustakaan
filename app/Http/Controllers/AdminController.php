<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\LoanController;

class AdminController extends Controller
{
    public function dashboard()
    {
        Loan::updateOverdueStatuses();

        $totalCollection   = Book::count();
        $activeLoans       = Loan::whereIn('status', ['borrowed', 'overdue'])->count();
        $newMembersMonth   = User::where('role', 'member')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $overdueAlerts     = Loan::where('status', 'overdue')->count();

        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::today()->subDays($i);
            $trends[] = [
                'day'   => $date->format('D'),
                'count' => Loan::whereDate('borrowed_date', $date)->count(),
            ];
        }

        $recentActivities = Loan::with(['user', 'book'])->latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalCollection',
            'activeLoans',
            'newMembersMonth',
            'overdueAlerts',
            'trends',
            'recentActivities'
        ));
    }

    // ── BOOKS ─────────────────────────────────────────────────

    public function books(Request $request)
    {
        $query = Book::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($b) use ($q) {
                $b->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books              = $query->latest()->paginate(15)->withQueryString();
        $existingCategories = Book::getExistingCategories();

        return view('admin.books.index', compact('books', 'existingCategories'));
    }

    public function createBook()
    {
        $existingCategories = Book::getExistingCategories();
        return view('admin.books.create', compact('existingCategories'));
    }

    public function storeBook(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'category'       => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'stok'           => 'required|integer|min:1',
            'cover_image'    => 'nullable|image|max:2048',
        ]);

        $data['available_copies'] = $data['stok'];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function editBook(Book $book)
    {
        $existingCategories = Book::getExistingCategories();
        return view('admin.books.edit', compact('book', 'existingCategories'));
    }

    public function updateBook(Request $request, Book $book)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'published_year'   => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'category'         => 'required|string|max:100',
            'deskripsi'        => 'nullable|string',
            'stok'             => 'required|integer|min:0',
            'available_copies' => 'required|integer|min:0',
            'cover_image'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroyBook(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    // ── MEMBERS ────────────────────────────────────────────────

    public function members(Request $request)
    {
        $query = User::where('role', 'member');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($u) use ($q) {
                $u->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('member_id', 'like', "%{$q}%");
            });
        }

        $members = $query->withCount(['loans', 'activeLoans'])->latest()->paginate(15)->withQueryString();
        return view('admin.members.index', compact('members'));
    }

    // ── LOANS ──────────────────────────────────────────────────

    public function loans(Request $request)
    {
        Loan::updateOverdueStatuses();

        $query = Loan::with(['user', 'book'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$q}%"))
                    ->orWhere('record_id', 'like', "%{$q}%");
            });
        }

        $loans = $query->paginate(15)->withQueryString();
        return view('admin.loans.index', compact('loans'));
    }

    // Admin: konfirmasi pengajuan → buku resmi dipinjam, stok berkurang
    public function confirmBorrow(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        if ($loan->book->available_copies <= 0) {
            return back()->with('error', 'Stok buku sudah habis.');
        }

        $loan->update([
            'status'        => 'borrowed',
            'borrowed_date' => Carbon::today(),
            'due_date'      => Carbon::today()->addDays(LoanController::LOAN_DAYS),
        ]);

        // Kurangi stok baru saat dikonfirmasi
        $loan->book->decrement('available_copies');

        return back()->with('success', "Peminjaman \"{$loan->book->title}\" oleh {$loan->user->name} dikonfirmasi.");
    }

    // Admin: tolak pengajuan
    public function rejectBorrow(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $loan->update(['status' => 'rejected']);

        return back()->with('success', "Pengajuan \"{$loan->book->title}\" ditolak.");
    }

    // Admin: konfirmasi pengembalian fisik → stok bertambah
    public function confirmReturn(Loan $loan)
    {
        if (!in_array($loan->status, ['borrowed', 'overdue'])) {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        $loan->update([
            'status'        => 'returned',
            'returned_date' => Carbon::today(),
        ]);

        $loan->book->increment('available_copies');

        return back()->with('success', "Pengembalian \"{$loan->book->title}\" oleh {$loan->user->name} dikonfirmasi.");
    }
}
