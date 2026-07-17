<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Sembunyikan buku yang stoknya 0 (sedang dipinjam semua)
        $query->where('available_copies', '>', 0);

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('author', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'title'  => $query->orderBy('title'),
            'author' => $query->orderBy('author'),
            'rating' => $query->orderBy('rating', 'desc'),
            default  => $query->latest(),
        };

        $books              = $query->paginate(12)->withQueryString();
        $existingCategories = Book::getExistingCategories();
        $totalBooks         = Book::where('available_copies', '>', 0)->count();

        return view('catalog.index', compact('books', 'existingCategories', 'totalBooks'));
    }

    public function show(Book $book)
    {
        $related = Book::where('category', $book->category)
            ->where('id', '!=', $book->id)
            ->where('available_copies', '>', 0)
            ->limit(5)
            ->get();

        $isSaved = false;
        if (auth()->check()) {
            $isSaved = auth()->user()->wishlists()->where('book_id', $book->id)->exists();
        }

        $isAlreadyBorrowed = false;
        if (auth()->check()) {
            $isAlreadyBorrowed = auth()->user()->loans()
                ->where('book_id', $book->id)
                ->whereIn('status', ['pending', 'borrowed', 'overdue'])
                ->exists();
        }

        return view('catalog.show', compact('book', 'related', 'isSaved', 'isAlreadyBorrowed'));
    }
}
