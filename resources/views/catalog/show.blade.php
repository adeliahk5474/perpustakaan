{{-- resources/views/catalog/show.blade.php --}}
@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('catalog.index') }}" class="hover:text-[#1B2A5E]">Home</a>
        <span class="mx-2">›</span>
        <a href="{{ route('catalog.index') }}" class="hover:text-[#1B2A5E]">Catalog</a>
        <span class="mx-2">›</span>
        <span class="text-gray-800">{{ $book->title }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- LEFT: Cover --}}
        <div>
            <div class="rounded-2xl overflow-hidden bg-gradient-to-br from-slate-700 to-slate-900 h-80 flex items-center justify-center shadow-lg">
                @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                <span class="text-8xl">📖</span>
                @endif
            </div>
            <div class="mt-4 flex items-center gap-2 px-4 py-3 rounded-xl {{ $book->available_copies > 0 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                <span class="w-3 h-3 rounded-full {{ $book->available_copies > 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                <span class="text-sm font-semibold {{ $book->available_copies > 0 ? 'text-green-700' : 'text-red-700' }}">
                    {{ $book->available_copies > 0 ? 'AVAILABLE NOW' : 'NOT AVAILABLE' }}
                </span>
                @if($book->available_copies > 0)
                <span class="text-xs text-gray-500 ml-auto">{{ $book->available_copies }} copies left</span>
                @endif
            </div>
        </div>

        {{-- RIGHT: Details --}}
        <div class="md:col-span-2">
            <div class="flex gap-2 mb-3">
                <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-3 py-1 rounded-full uppercase tracking-wide">{{ $book->category }}</span>
                @if($book->is_ebook)
                <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-3 py-1 rounded-full">E-BOOK</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $book->title }}</h1>
            <p class="text-gray-500 mb-4">By {{ $book->author }}</p>

            <div class="flex items-center gap-6 text-sm text-gray-500 mb-6 pb-4 border-b border-gray-200">
                @if($book->rating > 0)
                <span class="flex items-center gap-1">
                    <span class="text-yellow-400">★</span>
                    <strong class="text-gray-800">{{ number_format($book->rating, 1) }}</strong>
                    ({{ number_format($book->rating_count) }} reviews)
                </span>
                @endif
                @if($book->pages)
                <span>📖 {{ number_format($book->pages) }} Pages</span>
                @endif
                <span>🌐 {{ $book->language }}</span>
            </div>

            @if($book->synopsis)
            <div class="mb-6">
                <h2 class="font-bold text-gray-900 mb-2 text-lg">Synopsis</h2>
                <p class="text-gray-600 leading-relaxed text-sm">{{ $book->synopsis }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4 mb-6">
                {{-- Specifications --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1">ℹ️ Specifications</p>
                    @if($book->isbn)
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">ISBN-13</span>
                        <span class="font-medium">{{ $book->isbn }}</span>
                    </div>
                    @endif
                    @if($book->published_year)
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Published</span>
                        <span class="font-medium">{{ $book->published_year }}</span>
                    </div>
                    @endif
                    @if($book->publisher)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Publisher</span>
                        <span class="font-medium">{{ $book->publisher }}</span>
                    </div>
                    @endif
                </div>

                {{-- Shelf Location --}}
                @if($book->shelf_location)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-3 flex items-center gap-1">📍 Shelf Location</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $book->shelf_location }}</p>
                    @if($book->shelf_section)
                    <p class="text-sm text-gray-500">{{ $book->shelf_section }}</p>
                    @endif
                </div>
                @endif
            </div>

            {{-- CTA --}}
            <div class="flex gap-3">
                @auth
                @if($isAlreadyBorrowed)
                <div class="flex-1 bg-gray-100 text-gray-500 py-3.5 rounded-xl font-semibold text-center text-sm">
                    ✓ Sedang Dipinjam
                </div>
                @elseif($book->available_copies > 0)
                <form action="{{ route('loans.borrow', $book) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full bg-[#1B2A5E] text-white py-3.5 rounded-xl font-semibold hover:bg-[#0F1D45] transition flex items-center justify-center gap-2">
                        📖 Borrow This Book
                    </button>
                </form>
                @else
                <div class="flex-1 bg-gray-100 text-gray-500 py-3.5 rounded-xl font-semibold text-center text-sm">
                    Tidak Tersedia
                </div>
                @endif

                <form action="{{ route('books.wishlist', $book) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-3.5 rounded-xl border {{ $isSaved ? 'border-[#1B2A5E] bg-[#1B2A5E] text-white' : 'border-gray-300 text-gray-600 hover:border-[#1B2A5E]' }} transition">
                        {{ $isSaved ? '★' : '☆' }}
                    </button>
                </form>

                @else
                <a href="{{ route('login') }}"
                    class="flex-1 bg-[#1B2A5E] text-white py-3.5 rounded-xl font-semibold hover:bg-[#0F1D45] transition flex items-center justify-center gap-2 text-sm">
                    🔐 Login untuk Meminjam
                </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($related->isNotEmpty())
    <div class="mt-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-[#1B2A5E]">Related in {{ $book->category }}</h2>
            <a href="{{ route('catalog.index', ['category' => $book->category]) }}" class="text-sm text-[#1B2A5E] hover:underline font-semibold">VIEW ALL →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($related as $rel)
            <a href="{{ route('books.show', $rel) }}" class="group block">
                <div class="rounded-xl overflow-hidden h-36 bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center mb-2 group-hover:opacity-80 transition">
                    @if($rel->cover_image)
                    <img src="{{ asset('storage/' . $rel->cover_image) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                    @else
                    <span class="text-4xl">📖</span>
                    @endif
                </div>
                <p class="text-sm font-semibold text-gray-800 group-hover:text-[#1B2A5E] line-clamp-2">{{ $rel->title }}</p>
                <p class="text-xs text-gray-500">{{ $rel->author }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
