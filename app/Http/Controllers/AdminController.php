<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $query->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$q}%"))
                ->orWhere('record_id', 'like', "%{$q}%");
        }

        $loans = $query->paginate(15)->withQueryString();
        return view('admin.loans.index', compact('loans'));
    }

    public function confirmReturn(Loan $loan)
    {
        if ($loan->status === 'returned') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $loan->update(['status' => 'returned', 'returned_date' => Carbon::today()]);
        $loan->book->increment('available_copies');

        return back()->with('success', 'Pengembalian buku dikonfirmasi.');
    }
}
