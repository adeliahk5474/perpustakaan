{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Perpustakaan</h1>
        <p class="text-gray-500 text-sm mt-1">Ringkasan aktivitas peminjaman dan pengembalian buku.</p>
    </div>
    <form action="{{ route('admin.reports') }}" method="GET" class="flex items-center gap-2">
        <select name="period" onchange="this.form.submit()"
            class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
            <option value="7" {{ request('period', '30') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
            <option value="90" {{ request('period', '30') == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
            <option value="365" {{ request('period', '30') == '365' ? 'selected' : '' }}>1 Tahun Terakhir</option>
        </select>
        <button type="submit" class="bg-[#1B2A5E] text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-[#0F1D45]">
            Terapkan
        </button>
        <a href="{{ route('admin.reports.export', ['period' => request('period', '30')]) }}"
            class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50 flex items-center gap-1">
            ⬇️ Ekspor CSV
        </a>
    </form>
</div>

{{-- RINGKASAN STATISTIK --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl mb-3">📖</div>
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Peminjaman</p>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalLoans ?? 0) }}</p>
        <p class="text-xs text-gray-400 mt-1">Pada periode terpilih</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-xl mb-3">🔔</div>
        <p class="text-xs text-gray-400 uppercase tracking-wide">Berhasil Dikembalikan</p>
        <p class="text-3xl font-bold text-green-700">{{ number_format($totalReturned ?? 0) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $returnRate ?? 0 }}% tingkat pengembalian</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-xl mb-3">⚠️</div>
        <p class="text-xs text-gray-400 uppercase tracking-wide">Terlambat Dikembalikan</p>
        <p class="text-3xl font-bold text-red-600">{{ number_format($totalOverdue ?? 0) }}</p>
        <p class="text-xs text-gray-400 mt-1">Butuh tindak lanjut</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-xl mb-3">💰</div>
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Denda</p>
        <p class="text-3xl font-bold text-gray-900">Rp{{ number_format($totalFines ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Akumulasi denda keterlambatan</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    {{-- GRAFIK TREN PEMINJAMAN --}}
    <div class="col-span-2 bg-white border border-gray-200 rounded-2xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-bold text-gray-900">Tren Peminjaman & Pengembalian</h2>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#1B2A5E]"></span> Dipinjam</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-400"></span> Dikembalikan</span>
            </div>
        </div>
        @php
        $trendData = $trends ?? [];
        $maxVal = collect($trendData)->flatMap(fn($t) => [$t['borrowed'] ?? 0, $t['returned'] ?? 0])->max() ?: 1;
        @endphp
        <div class="flex items-end gap-3 h-48">
            @forelse($trendData as $t)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex items-end gap-0.5 h-40">
                    <div class="flex-1 bg-[#1B2A5E] rounded-t-md" style="height: {{ max((($t['borrowed'] ?? 0) / $maxVal) * 100, 3) }}%" title="{{ $t['borrowed'] ?? 0 }} dipinjam"></div>
                    <div class="flex-1 bg-green-400 rounded-t-md" style="height: {{ max((($t['returned'] ?? 0) / $maxVal) * 100, 3) }}%" title="{{ $t['returned'] ?? 0 }} dikembalikan"></div>
                </div>
                <span class="text-[10px] text-gray-400">{{ $t['label'] ?? '' }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 m-auto">Belum ada data untuk periode ini.</p>
            @endforelse
        </div>
    </div>

    {{-- KATEGORI TERPOPULER --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h2 class="font-bold text-gray-900 mb-4">Kategori Terpopuler</h2>
        @forelse(($topCategories ?? []) as $cat)
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-gray-700">{{ $cat['category'] }}</span>
                <span class="text-gray-400">{{ $cat['count'] }} peminjaman</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-[#1B2A5E] h-2 rounded-full" style="width: {{ $cat['percentage'] }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-6">Belum ada data kategori.</p>
        @endforelse
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-6">
    {{-- BUKU PALING SERING DIPINJAM --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">🏆 Buku Paling Sering Dipinjam</h2>
        <div class="space-y-3">
            @forelse(($mostBorrowedBooks ?? []) as $i => $book)
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-500">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $book->title }}</p>
                    <p class="text-xs text-gray-400">{{ $book->author }}</p>
                </div>
                <span class="text-xs font-bold text-[#1B2A5E] bg-blue-50 px-2 py-1 rounded-full">{{ $book->loans_count ?? 0 }}x</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data peminjaman.</p>
            @endforelse
        </div>
    </div>

    {{-- ANGGOTA PALING AKTIF --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">⭐ Anggota Paling Aktif</h2>
        <div class="space-y-3">
            @forelse(($mostActiveMembers ?? []) as $i => $member)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-[#1B2A5E] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $member->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $member->email }}</p>
                </div>
                <span class="text-xs font-bold text-green-700 bg-green-50 px-2 py-1 rounded-full">{{ $member->loans_count ?? 0 }}x pinjam</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data anggota.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- TABEL DETAIL TRANSAKSI --}}
<div class="bg-white border border-gray-200 rounded-2xl">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-900">Detail Transaksi Peminjaman</h2>
        <span class="text-xs text-gray-400">{{ $transactions->total() ?? 0 }} catatan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-200">
                    <th class="text-left px-6 py-3 font-medium">ID</th>
                    <th class="text-left px-6 py-3 font-medium">Anggota</th>
                    <th class="text-left px-6 py-3 font-medium">Buku</th>
                    <th class="text-left px-6 py-3 font-medium">Tgl Pinjam</th>
                    <th class="text-left px-6 py-3 font-medium">Tenggat</th>
                    <th class="text-left px-6 py-3 font-medium">Tgl Kembali</th>
                    <th class="text-center px-6 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse(($transactions ?? []) as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500 font-mono text-xs">#{{ $trx->record_id ?? $trx->id }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $trx->user->name }}</td>
                    <td class="px-6 py-3 text-gray-700 truncate max-w-xs">{{ $trx->book->title }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ optional($trx->borrowed_date)->format('d M Y') }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ optional($trx->due_date)->format('d M Y') }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ optional($trx->returned_date)->format('d M Y') ?? '-' }}</td>
                    <td class="px-6 py-3 text-center">
                        @php
                        $statusClasses = [
                        'returned' => 'bg-green-100 text-green-700',
                        'overdue' => 'bg-red-100 text-red-600',
                        'borrowed' => 'bg-blue-100 text-blue-700',
                        ];
                        $statusLabels = [
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        'borrowed' => 'Dipinjam',
                        ];
                        @endphp
                        <span class="{{ $statusClasses[$trx->status] ?? 'bg-gray-100 text-gray-600' }} text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $statusLabels[$trx->status] ?? $trx->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($transactions) && method_exists($transactions, 'links'))
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $transactions->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>
@endsection
