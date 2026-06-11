{{-- resources/views/catalog/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Home/Catalog')

@section('content')
{{-- HERO --}}
<div class="relative h-72 bg-cover bg-center overflow-hidden"
    style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=1400&q=80')">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white px-4">
        <h1 class="text-4xl font-bold mb-2">Explore the World of Knowledge</h1>
        <p class="text-gray-200 mb-6">Access over {{ number_format($totalBooks) }} digital and physical resources from our curated archive.</p>
        <form action="{{ route('catalog.index') }}" method="GET" class="w-full max-w-xl">
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Search by title, author, or ISBN..."
                    class="w-full pl-11 pr-4 py-3.5 rounded-xl text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/50 shadow-lg">
            </div>
        </form>
    </div>
</div>

{{-- MAIN --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex gap-8">
        {{-- SIDEBAR FILTERS --}}
        <aside class="w-56 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 sticky top-20">
                <h2 class="font-bold text-[#1B2A5E] text-lg mb-4">Filters</h2>

                <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
                    @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Category</p>
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-2 mb-2 cursor-pointer">
                        <input type="checkbox" name="category" value="{{ $cat }}"
                            {{ request('category') === $cat ? 'checked' : '' }}
                            onchange="document.getElementById('filter-form').submit()"
                            class="rounded accent-[#1B2A5E]">
                        <span class="text-sm text-gray-700">{{ $cat }}</span>
                    </label>
                    @endforeach

                    <hr class="my-4 border-gray-200">

                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Availability</p>
                    @foreach(['available' => 'Available Now', 'ebook' => 'E-Books Only', '' => 'All Items'] as $val => $label)
                    <label class="flex items-center gap-2 mb-2 cursor-pointer">
                        <input type="radio" name="availability" value="{{ $val }}"
                            {{ request('availability', '') === $val ? 'checked' : '' }}
                            onchange="document.getElementById('filter-form').submit()"
                            class="accent-[#1B2A5E]">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </form>
            </div>
        </aside>

        {{-- BOOKS GRID --}}
        <div class="flex-1">
            <div class="flex justify-between items-center mb-5">
                <p class="text-sm text-gray-600">Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ number_format($books->total()) }} results</p>
                <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except('sort') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <label class="text-sm text-gray-500">SORT BY</label>
                    <select name="sort" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg text-sm px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Most Recent</option>
                        <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title A–Z</option>
                        <option value="author" {{ request('sort') === 'author' ? 'selected' : '' }}>Author</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
                    </select>
                </form>
            </div>

            @if($books->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <p class="text-5xl mb-3">📭</p>
                <p class="text-lg font-medium">Tidak ada buku ditemukan</p>
                <p class="text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                <a href="{{ route('catalog.index') }}" class="mt-4 inline-block text-[#1B2A5E] text-sm hover:underline">Reset semua filter</a>
            </div>
            @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($books as $book)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition group">
                    <a href="{{ route('books.show', $book) }}" class="block relative">
                        <div class="h-44 bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center overflow-hidden">
                            @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-5xl">📖</span>
                            @endif
                        </div>
                        <div class="absolute top-2 left-2">
                            @if($book->available_copies > 0)
                            <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">AVAILABLE</span>
                            @else
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">BORROWED</span>
                            @endif
                        </div>
                        @if($book->is_ebook)
                        <div class="absolute top-2 right-2">
                            <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">E-BOOK</span>
                        </div>
                        @endif
                    </a>
                    <div class="p-3">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h3 class="text-sm font-semibold text-[#1B2A5E] leading-snug hover:underline line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $book->author }}</p>
                            @if($book->rating > 0)
                            <p class="text-xs text-yellow-500 mt-1">★ {{ number_format($book->rating, 1) }}</p>
                            @endif
                        </a>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100">
                            <span class="text-[10px] text-gray-400">{{ $book->isbn }}</span>
                            <a href="{{ route('books.show', $book) }}" class="text-xs text-[#1B2A5E] font-semibold hover:underline">DETAILS ›</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8 flex justify-center">
                {{ $books->links('vendor.pagination.tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
