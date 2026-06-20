{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title', 'Dasbor Admin')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-900">Ringkasan Dasbor</h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, Admin. Berikut yang terjadi hari ini.</p>

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 bg-[#1B2A5E] rounded-xl flex items-center justify-center text-white">📚</div>
                <span class="text-xs text-green-600 font-semibold">↑ +12%</span>
            </div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mt-3">Total Koleksi</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCollection) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">📋</div>
                <span class="text-xs text-gray-400 font-semibold">Aktif</span>
            </div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mt-3">Peminjaman Aktif</p>
            <p class="text-3xl font-bold text=" gray-900">{{ number_format($activeLoans) }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">👥</div>
                <span class="text-xs text-green-600 font-semibold">↑ +5%</span>
            </div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mt-3">Anggota Baru (Bulan Ini)</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($newMembersMonth) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-4">
        {{-- CHART --}}
        <div class="col-span-2 bg-white border border-gray-200 rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-900">Tren Sirkulasi</h2>
                <span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-500">7 Hari Terakhir</span>
            </div>
            @php
            $counts = array_column($trends, 'count');
            $max = $counts ? max(max($counts), 1) : 1;
            @endphp
            <div class="flex items-end gap-2 h-40">
                @foreach($trends as $trend)
                @php $height = ($trend['count'] / $max) * 100; @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-t-md {{ $loop->last ? 'bg-[#1B2A5E]' : 'bg-gray-200' }} transition-all"
                        style="height: {{ max($height, 4) }}%"
                        title="{{ $trend['count'] }} peminjaman"></div>
                    <span class="text-xs text-gray-400">{{ $trend['day'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- SYSTEM HEALTH --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 mb-4">Kondisi Sistem</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl">
                    <span class="text-xl">✅</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Server Online</p>
                        <p class="text-xs text-gray-500">Waktu Aktif: 99.9%</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 {{ $overdueAlerts > 0 ? 'bg-orange-50' : 'bg-gray-50' }} rounded-xl">
                    <span class="text-xl">{{ $overdueAlerts > 0 ? '⚠️' : '✅' }}</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $overdueAlerts }} Peringatan Terlambat</p>
                        <p class="text-xs text-gray-500">{{ $overdueAlerts > 0 ? 'Memerlukan notifikasi' : 'Semua aman' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <span class="text-xl">💾</span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Pencadangan Selesai</p>
                        <p class="text-xs text-gray-500">Hari ini, 02:00 Pagi</p>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.loans') }}" method="GET">
                <input type="hidden" name="status" value="overdue">
                <button type="submit" class="w-full mt-4 bg-[#1B2A5E] text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                    Lihat Peminjaman Terlambat
                </button>
            </form>
        </div>
    </div>

    {{-- RECENT ACTIVITIES --}}
    <div class="bg-white border border-gray-200 rounded-2xl mt-4">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Aktivitas Terbaru</h2>
            <a href="{{ route('admin.loans') }}" class="text-sm text-[#1B2A5E] hover:underline font-semibold">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-gray-400 uppercase tracking-wide border-b border-gray-100">
                        <th class="text-left px-6 py-3 font-medium">ID Catatan</th>
                        <th class="text-left px-6 py-3 font-medium">Anggota</th>
                        <th class="text-left px-6 py-3 font-medium">Judul Buku</th>
                        <th class="text-left px-6 py-3 font-medium">Status</th>
                        <th class="text-left px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentActivities as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">#{{ $loan->record_id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#1B2A5E] flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $loan->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 truncate max-w-xs">{{ $loan->book->title }}</td>
                        <td class="px-6 py-4">
                            @php
                            $statusClasses = [
                            'returned' => 'bg-green-100 text-green-700',
                            'overdue' => 'bg-red-100 text-red-600',
                            'borrowed' => 'bg-blue-100 text-blue-700',
                            ];
                            $statusLabels = [
                            'returned' => 'dikembalikan',
                            'overdue' => 'terlambat',
                            'borrowed' => 'dipinjam',
                            ];
                            @endphp
                            <span class="{{ $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-600' }} text-xs font-bold px-2 py-0.5 rounded-full uppercase">
                                {{ $statusLabels[$loan->status] ?? $loan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $loan->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if($loan->status !== 'returned')
                            <form action="{{ route('admin.loans.confirm-return', $loan) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-[#1B2A5E] hover:underline font-semibold">
                                    Konfirmasi Pengembalian
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection