@extends('layouts.admin')
@section('title', 'Edit Buku')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.books') }}" class="text-gray-400 hover:text-gray-700">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Buku</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-4">
        @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data"
        class="bg-white border border-gray-200 rounded-2xl p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-2 gap-5">

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Judul Buku *</label>
                <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penulis *</label>
                <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tahun Terbit</label>
                <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}"
                    min="1000" max="{{ date('Y') + 1 }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Kategori *</label>
                <input type="text" name="category" value="{{ old('category', $book->category) }}" required
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

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 resize-none">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Stok Buku *</label>
                <input type="number" name="stok" value="{{ old('stok', $book->stok) }}" min="0" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tersedia Sekarang</label>
                <input type="number" name="available_copies" value="{{ old('available_copies', $book->available_copies) }}"
                    min="0"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 bg-gray-50">
                <p class="text-xs text-gray-400 mt-1">Jumlah yang tidak sedang dipinjam.</p>
            </div>

            <div class="col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Gambar Sampul</label>
                @if($book->cover_image)
                <div class="flex items-center gap-4 mb-2">
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-16 h-20 object-cover rounded-lg border">
                    <p class="text-xs text-gray-500">Cover saat ini. Upload baru untuk mengganti.</p>
                </div>
                @endif
                <input type="file" name="cover_image" accept="image/*"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#1B2A5E] file:text-white file:text-xs file:cursor-pointer">
            </div>

        </div>

        <div class="flex gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="bg-[#1B2A5E] text-white px-8 py-3 rounded-xl font-semibold hover:bg-[#0F1D45] transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.books') }}" class="border border-gray-300 text-gray-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
