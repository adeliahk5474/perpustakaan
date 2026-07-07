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

            {{-- Judul --}}
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Judul Buku *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            {{-- Penulis --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penulis *</label>
                <input type="text" name="author" value="{{ old('author') }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            {{-- Penerbit --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            {{-- Tahun Terbit --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tahun Terbit</label>
                <input type="number" name="published_year" value="{{ old('published_year') }}" min="1000" max="{{ date('Y') + 1 }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            {{-- Kategori (input manual) --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Kategori *</label>
                <input type="text" name="category" value="{{ old('category') }}" required
                    placeholder="Contoh: Novel, Sains, Sejarah..."
                    list="category-suggestions"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                <datalist id="category-suggestions">
                    @foreach($existingCategories as $cat)
                    <option value="{{ $cat }}">
                        @endforeach
                </datalist>
                <p class="text-xs text-gray-400 mt-1">Ketik bebas atau pilih dari kategori yang sudah ada.</p>
            </div>

            {{-- Deskripsi (bukan sinopsis) --}}
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 resize-none"
                    placeholder="Tulis deskripsi singkat buku ini...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Stok --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Stok Buku *</label>
                <input type="number" name="stok" value="{{ old('stok', 1) }}" min="1" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            {{-- Gambar Sampul --}}
            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Gambar Sampul</label>
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
