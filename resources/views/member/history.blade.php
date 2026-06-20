{{-- resources/views/member/history.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-gray-700">← My Books</a>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-200">
                    <th class="text-left px-6 py-3 font-medium">Buku</th>
                    <th class="text-left px-6 py-3 font-medium">Dipinjam</th>
                    <th class="text-left px-6 py-3 font-medium">Jatuh Tempo</th>
                    <th class="text-left px-6 py-3 font-medium">Dikembalikan</th>
                    <th class="text-left px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('books.show', $loan->book) }}" class="font-semibold text-[#1B2A5E] hover:underline line-clamp-1">
                            {{ $loan->book->title }}
                        </a>
                        <p class="text-xs text-gray-400">{{ $loan->book->author }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $loan->borrowed_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $loan->due_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $loan->returned_date?->format('d M Y') ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php $wasLate = $loan->returned_date && $loan->returned_date->gt($loan->due_date); @endphp
                        <span class="{{ $wasLate ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }} text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $wasLate ? 'LATE RETURN' : 'ON TIME' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('loans.borrow', $loan->book) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-[#1B2A5E] font-semibold hover:underline">RE-BORROW</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <p class="text-4xl mb-2">📭</p>
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $loans->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
@endsection