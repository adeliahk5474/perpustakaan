{{-- resources/views/admin/members/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Members')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Daftar Member</h1>
    <span class="text-sm text-gray-500">{{ $members->total() }} member terdaftar</span>
</div>

<form action="{{ route('admin.members') }}" method="GET" class="flex gap-3 mb-5">
    <div class="relative flex-1 max-w-sm">
        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau member ID..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
    </div>
    <button type="submit" class="bg-[#1B2A5E] text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-[#0F1D45]">Cari</button>
    @if(request('q'))
    <a href="{{ route('admin.members') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm hover:bg-gray-50">Reset</a>
    @endif
</form>

<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide border-b border-gray-200">
                <th class="text-left px-6 py-3 font-medium">Member</th>
                <th class="text-left px-6 py-3 font-medium">Member ID</th>
                <th class="text-left px-6 py-3 font-medium">Bergabung</th>
                <th class="text-center px-6 py-3 font-medium">Total Pinjam</th>
                <th class="text-center px-6 py-3 font-medium">Aktif</th>
                <th class="text-center px-6 py-3 font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($members as $member)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#1B2A5E] flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $member->name }}</p>
                            <p class="text-xs text-gray-400">{{ $member->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $member->member_id }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $member->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $member->loans_count }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-bold {{ $member->active_loans_count > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                        {{ $member->active_loans_count }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">Active</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    Tidak ada member ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $members->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection