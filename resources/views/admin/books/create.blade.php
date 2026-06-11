{{-- resources/views/admin/books/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tambah Buku')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.books') }}" class="text-gray-400 hover:text-gray-700">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Buku Baru</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-4">
        @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white border border-gray-200 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Judul Buku *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penulis *</label>
                <input type="text" name="author" value="{{ old('author') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tahun Terbit</label>
                <input type="number" name="published_year" value="{{ old('published_year') }}" min="1000" max="{{ date('Y') + 1 }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Kategori *</label>
                <select name="category" required class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    <option value="">Pilih kategori...</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Sinopsis</label>
                <textarea name="synopsis" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 resize-none">{{ old('synopsis') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Total Salinan *</label>
                <input type="number" name="total_copies" value="{{ old('total_copies', 1) }}" min="1" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Jumlah Halaman</label>
                <input type="number" name="pages" value="{{ old('pages') }}" min="1"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Lokasi Rak</label>
                <input type="text" name="shelf_location" value="{{ old('shelf_location') }}" placeholder="Section A, Row 12"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Seksi</label>
                <input type="text" name="shelf_section" value="{{ old('shelf_section') }}" placeholder="Computer Science Wing"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Bahasa</label>
                <input type="text" name="language" value="{{ old('language', 'Indonesian') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_ebook" value="1" id="is_ebook" {{ old('is_ebook') ? 'checked' : '' }}
                    class="w-4 h-4 accent-[#1B2A5E]">
                <label for="is_ebook" class="text-sm text-gray-700">Ini adalah E-Book</label>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#1B2A5E] file:text-white file:text-xs file:cursor-pointer">
            </div>
        </div>

        <div class="flex gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="bg-[#1B2A5E] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#0F1D45] transition">
                Simpan Buku
            </button>
            <a href="{{ route('admin.books') }}" class="border border-gray-300 text-gray-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
