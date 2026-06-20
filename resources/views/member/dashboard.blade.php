{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Buku Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-xs font-semibold text-[#1B2A5E] uppercase tracking-widest mb-1">DASBOR PENGGUNA</p>
            <h1 class="text-3xl font-bold text-gray-900">Aktivitas Membaca Anda</h1>
        </div>
        <a href="{{ route('catalog.index') }}"
            class="bg-[#1B2A5E] text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-[#0F1D45] transition text-sm flex items-center gap-2">
            + Pinjam Buku Baru
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">📚</div>
            <div>
                <p class="text-xs text-gray-500">Sedang Dipinjam</p>
                <p class="text-3xl font-bold text-gray-900">{{ str_pad($currentlyBorrowed, 2, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-400">/ 05 Batas</p>
            </div>
        </div>
        <div class="bg-white border {{ $overdueBooks > 0 ? 'border-red-300' : 'border-gray-200' }} rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 {{ $overdueBooks > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center text-2xl">
                {{ $overdueBooks > 0 ? '⚠️' : '✅' }}
            </div>
            <div>
                <p class="text-xs text-gray-500">Buku Terlambat</p>
                <p class="text-3xl font-bold {{ $overdueBooks > 0 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ str_pad($overdueBooks, 2, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">📅</div>
            <div>
                <p class="text-xs text-gray-500">Jatuh Tempo Minggu Ini</p>
                <p class="text-3xl font-bold text-gray-900">{{ str_pad($dueSoon, 2, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    {{-- ACTIVE LOANS --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
        <h2 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">📋 Peminjaman Aktif</h2>

        @if($activeLoans->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p class="text-4xl mb-2">📭</p>
            <p>Tidak ada peminjaman aktif.</p>
            <a href="{{ route('catalog.index') }}" class="text-[#1B2A5E] text-sm hover:underline mt-2 inline-block">Jelajahi katalog →</a>
        </div>
        @else
        <div class="space-y-3">
            @foreach($activeLoans as $loan)
            <div class="flex items-center gap-4 p-4 rounded-xl border {{ $loan->status === 'overdue' ? 'border-red-200 bg-red-50' : 'border-gray-100' }}">
                {{-- Cover --}}
                <div class="w-14 h-16 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                    @if($loan->book->cover_image)
                    <img src="{{ asset('storage/' . $loan->book->cover_image) }}" class="w-full h-full object-cover">
                    @else
                    <span class="text-2xl">📖</span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('books.show', $loan->book) }}" class="font-semibold text-[#1B2A5E] hover:underline text-sm">{{ $loan->book->title }}</a>
                    <p class="text-xs text-gray-500">Oleh {{ $loan->book->author }}</p>
                    <p class="text-xs text-gray-400">ISBN: {{ $loan->book->isbn }}</p>
                </div>

                {{-- Dates --}}
                <div class="text-center hidden md:block">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tanggal Pinjam</p>
                    <p class="text-sm font-medium">{{ $loan->borrowed_date->format('d M Y') }}</p>
                </div>

                <div class="text-center hidden md:block">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tenggat Pengembalian</p>
                    <p class="text-sm font-medium {{ $loan->status === 'overdue' ? 'text-red-600' : 'text-blue-600' }}">
                        {{ $loan->due_date->format('d M Y') }}
                    </p>
                    @if($loan->status === 'overdue')
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">
                        TERLAMBAT {{ $loan->days_late }} HARI
                    </span>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 flex-shrink-0">
                    @if($loan->renewal_count < 2 && $loan->status !== 'overdue')
                        <form action="{{ route('loans.renew', $loan) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 border border-gray-300 rounded-lg text-xs font-semibold hover:border-[#1B2A5E] hover:text-[#1B2A5E] transition">
                                PERPANJANG
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('loans.return', $loan) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-[#1B2A5E] text-white rounded-lg text-xs font-semibold hover:bg-[#0F1D45] transition">
                                KEMBALIKAN BUKU
                            </button>
                        </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- BOTTOM SECTION --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- RECENT HISTORY --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">🕐 Riwayat Terbaru</h2>
            @if($recentHistory->isEmpty())
            <p class="text-gray-400 text-sm text-center py-6">Belum ada riwayat peminjaman.</p>
            @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                        <th class="text-left pb-2 font-medium">Judul Buku</th>
                        <th class="text-left pb-2 font-medium">Dikembalikan</th>
                        <th class="text-left pb-2 font-medium">Status</th>
                        <th class="pb-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentHistory as $loan)
                    <tr>
                        <td class="py-3 pr-4">
                            <a href="{{ route('books.show', $loan->book) }}" class="text-[#1B2A5E] font-medium hover:underline line-clamp-1">
                                {{ $loan->book->title }}
                            </a>
                        </td>
                        <td class="py-3 text-gray-500 whitespace-nowrap">{{ $loan->returned_date?->format('d M Y') }}</td>
                        <td class="py-3">
                            @php
                            $wasLate = $loan->returned_date && $loan->returned_date->gt($loan->due_date);
                            @endphp
                            <span class="{{ $wasLate ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }} text-xs font-bold px-2 py-0.5 rounded-full">
                                {{ $wasLate ? 'TERLAMBAT' : 'TEPAT WAKTU' }}
                            </span>
                        </td>
                        <td class="py-3">
                            <form action="{{ route('loans.borrow', $loan->book) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-[#1B2A5E] font-semibold hover:underline">PINJAM LAGI</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('member.history') }}" class="block text-center text-sm text-[#1B2A5E] font-semibold hover:underline mt-4">
                LIHAT RIWAYAT LENGKAP
            </a>
            @endif
        </div>

        {{-- SAVED FOR LATER --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">★ Disimpan untuk Nanti</h2>
            @if($savedBooks->isEmpty())
            <p class="text-gray-400 text-sm text-center py-6">Belum ada buku yang disimpan.</p>
            @else
            <div class="space-y-3 mb-4">
                @foreach($savedBooks as $wish)
                <div class="flex gap-3 items-start">
                    <div class="w-12 h-14 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if($wish->book->cover_image)
                        <img src="{{ asset('storage/' . $wish->book->cover_image) }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-xl">📖</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <a href="{{ route('books.show', $wish->book) }}" class="text-sm font-semibold text-[#1B2A5E] hover:underline line-clamp-2">
                            {{ $wish->book->title }}
                        </a>
                        <p class="text-xs text-gray-500">{{ $wish->book->author }}</p>
                        <span class="{{ $wish->book->available_copies > 0 ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} text-[10px] font-bold px-2 py-0.5 rounded-full mt-1 inline-block">
                            {{ $wish->book->available_copies > 0 ? 'TERSEDIA' : 'DAFTAR TUNGGU' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <a href="{{ route('catalog.index') }}" class="block text-center text-sm text-[#1B2A5E] font-semibold hover:underline border border-[#1B2A5E] rounded-xl py-2">
                JELAJAHI SEMUA REKOMENDASI
            </a>
        </div>
    </div>
</div>
@endsection