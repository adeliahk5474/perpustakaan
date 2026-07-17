@extends('layouts.admin')
@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Manajemen Peminjaman</h1>
    <span class="text-sm text-gray-500">{{ $loans->total() }} total transaksi</span>
</div>

{{-- Filter --}}
<form action="{{ route('admin.loans') }}" method="GET" class="flex gap-3 mb-5 flex-wrap">
    <div class="relative flex-1 min-w-[200px] max-w-sm">
        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari member atau judul buku..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
    </div>
    <select name="status" class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
        <option value="">Semua Status</option>
        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ Menunggu Konfirmasi</option>
        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>📖 Sedang Dipinjam</option>
        <option value="overdue"  {{ request('status') === 'overdue'  ? 'selected' : '' }}>⚠️ Terlambat</option>
        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>✅ Dikembalikan</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
    </select>
    <button type="submit" class="bg-[#1B2A5E] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#0F1D45]">Filter</button>
    @if(request('q') || request('status'))
    <a href="{{ route('admin.loans') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-50">Reset</a>
    @endif
</form>

<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-200">
                <th class="text-left px-5 py-3 font-medium">No. Pengajuan</th>
                <th class="text-left px-5 py-3 font-medium">Member</th>
                <th class="text-left px-5 py-3 font-medium">Buku</th>
                <th class="text-left px-5 py-3 font-medium">Tgl Pinjam</th>
                <th class="text-left px-5 py-3 font-medium">Jatuh Tempo</th>
                <th class="text-left px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 text-center font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($loans as $loan)
            @php
            $rowBg = match($loan->status) {
                'pending'  => 'bg-yellow-50/60',
                'overdue'  => 'bg-red-50/50',
                default    => '',
            };
            $cls = [
                'pending'  => 'bg-yellow-100 text-yellow-700',
                'borrowed' => 'bg-blue-100 text-blue-700',
                'returned' => 'bg-green-100 text-green-700',
                'overdue'  => 'bg-red-100 text-red-600',
                'rejected' => 'bg-gray-100 text-gray-500',
            ];
            @endphp
            <tr class="hover:bg-gray-50 {{ $rowBg }}">
                <td class="px-5 py-4 text-gray-500 font-mono text-xs">#{{ $loan->record_id }}</td>

                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-[#1B2A5E] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $loan->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $loan->user->member_id }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-5 py-4">
                    <p class="font-medium text-gray-800 line-clamp-1 max-w-[180px]">{{ $loan->book->title }}</p>
                    <p class="text-xs text-gray-400">{{ $loan->book->author }}</p>
                </td>

                <td class="px-5 py-4 text-gray-500 whitespace-nowrap text-xs">
                    {{ $loan->borrowed_date?->format('d M Y') ?? '-' }}
                </td>

                <td class="px-5 py-4 whitespace-nowrap text-xs">
                    @if($loan->due_date)
                    <span class="{{ $loan->status === 'overdue' ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                        {{ $loan->due_date->format('d M Y') }}
                    </span>
                    @if($loan->status === 'overdue')
                    <p class="text-xs text-red-500">{{ $loan->days_late }} hari terlambat</p>
                    @endif
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>

                <td class="px-5 py-4">
                    <span class="{{ $cls[$loan->status] ?? 'bg-gray-100 text-gray-600' }} text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap">
                        {{ $loan->status_label }}
                    </span>
                </td>

                <td class="px-5 py-4">
                    <div class="flex gap-2 justify-center flex-wrap">
                        @if($loan->status === 'pending')
                            {{-- Admin konfirmasi → buku resmi dipinjam --}}
                            <form action="{{ route('admin.loans.confirm-borrow', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi peminjaman {{ $loan->book->title }} oleh {{ $loan->user->name }}?')"
                                    class="text-xs bg-[#1B2A5E] text-white px-3 py-1.5 rounded-lg hover:bg-[#0F1D45] font-semibold whitespace-nowrap">
                                    ✓ Konfirmasi Pinjam
                                </button>
                            </form>
                            <form action="{{ route('admin.loans.reject-borrow', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Tolak pengajuan ini?')"
                                    class="text-xs border border-red-300 text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-50 font-semibold whitespace-nowrap">
                                    ✗ Tolak
                                </button>
                            </form>

                        @elseif(in_array($loan->status, ['borrowed', 'overdue']))
                            {{-- Admin konfirmasi pengembalian fisik --}}
                            <form action="{{ route('admin.loans.confirm-return', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi buku {{ $loan->book->title }} sudah dikembalikan secara fisik?')"
                                    class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 font-semibold whitespace-nowrap">
                                    ↩ Konfirmasi Kembali
                                </button>
                            </form>

                        @elseif($loan->status === 'returned')
                            <span class="text-xs text-gray-400">{{ $loan->returned_date?->format('d M Y') }}</span>

                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    Tidak ada data peminjaman.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $loans->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection
