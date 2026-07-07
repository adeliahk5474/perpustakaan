{{-- resources/views/admin/books/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Inventaris')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Inventaris Buku</h1>
    <a href="{{ route('admin.books.create') }}"
        class="bg-[#1B2A5E] text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-[#0F1D45] transition text-sm flex items-center gap-2">
        + Tambah Buku Baru
    </a>
</div>

{{-- FILTERS --}}
<form action="{{ route('admin.books') }}" method="GET" class="flex gap-3 mb-5">
    <div class="relative flex-1 max-w-sm">
        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
    </div>
    <select name="category" class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
        <option value="">Semua Kategori</option>
        @foreach($existingCategories as $cat)
        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-[#1B2A5E] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#0F1D45]">Filter</button>
    @if(request('q') || request('category'))
    <a href="{{ route('admin.books') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-50">Reset</a>
    @endif
</form>

<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-200">
                <th class="text-left px-6 py-3 font-medium">Buku</th>
                <th class="text-left px-6 py-3 font-medium">Kategori</th>
                <th class="text-left px-6 py-3 font-medium">Penerbit</th>
                <th class="text-center px-6 py-3 font-medium">Stok</th>
                <th class="text-center px-6 py-3 font-medium">Tersedia</th>
                <th class="text-center px-6 py-3 font-medium">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($books as $book)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-12 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover">
                            @else
                            <span>📖</span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 line-clamp-1">{{ $book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $book->author }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $book->category ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $book->publisher ?? '-' }}</td>
                <td class="px-6 py-4 text-center font-medium">{{ $book->stok }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-bold {{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $book->available_copies }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="{{ $book->available_copies > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }} text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $book->available_copies > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.books.edit', $book) }}" class="text-xs text-[#1B2A5E] hover:underline font-semibold">Ubah</a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Yakin hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    Tidak ada buku ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $books->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection
