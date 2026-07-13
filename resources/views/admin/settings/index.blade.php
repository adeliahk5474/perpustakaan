{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
    <p class="text-gray-500 text-sm mt-1">Kelola aturan peminjaman, profil perpustakaan, dan preferensi akun.</p>
</div>

<div class="grid grid-cols-3 gap-6">
    {{-- SIDEBAR TAB --}}
    <div class="col-span-1">
        <div class="bg-white border border-gray-200 rounded-2xl p-3 sticky top-20">
            <a href="#profil-perpustakaan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                🏛️ Profil Perpustakaan
            </a>
            <a href="#aturan-peminjaman" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                📚 Aturan Peminjaman
            </a>
            <a href="#denda" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                💰 Denda & Sanksi
            </a>
            <a href="#notifikasi" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                🔔 Notifikasi
            </a>
            <a href="#akun" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                🔐 Akun & Keamanan
            </a>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="col-span-2 space-y-6">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif

        {{-- PROFIL PERPUSTAKAAN --}}
        <div id="profil-perpustakaan" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-1">🏛️ Profil Perpustakaan</h2>
            <p class="text-xs text-gray-400 mb-5">Informasi ini akan ditampilkan kepada anggota di halaman katalog.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="section" value="profil">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nama Perpustakaan</label>
                    <input type="text" name="library_name" value="{{ $settings['library_name'] ?? 'PerpusKu' }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Email Kontak</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@perpusku.edu' }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nomor Telepon</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="08xx-xxxx-xxxx"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Alamat</label>
                    <textarea name="address" rows="2"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 resize-none">{{ $settings['address'] ?? 'Digital Plaza, Lantai 2' }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Jam Operasional Buka</label>
                        <input type="time" name="open_time" value="{{ $settings['open_time'] ?? '08:00' }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Jam Operasional Tutup</label>
                        <input type="time" name="close_time" value="{{ $settings['close_time'] ?? '17:00' }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-[#1B2A5E] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- ATURAN PEMINJAMAN --}}
        <div id="aturan-peminjaman" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-1">📚 Aturan Peminjaman</h2>
            <p class="text-xs text-gray-400 mb-5">Atur batas dan ketentuan peminjaman buku untuk seluruh anggota.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="section" value="aturan_peminjaman">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Maksimal Buku Dipinjam / Anggota</label>
                        <input type="number" name="max_loans_per_member" min="1" value="{{ $settings['max_loans_per_member'] ?? 5 }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        <p class="text-xs text-gray-400 mt-1">Jumlah buku yang boleh dipinjam bersamaan oleh satu anggota.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Durasi Pinjam (hari)</label>
                        <input type="number" name="loan_duration_days" min="1" value="{{ $settings['loan_duration_days'] ?? 14 }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        <p class="text-xs text-gray-400 mt-1">Tenggat waktu pengembalian sejak tanggal pinjam.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Maksimal Perpanjangan</label>
                        <input type="number" name="max_renewals" min="0" value="{{ $settings['max_renewals'] ?? 2 }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        <p class="text-xs text-gray-400 mt-1">Berapa kali buku boleh diperpanjang.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Durasi Perpanjangan (hari)</label>
                        <input type="number" name="renewal_duration_days" min="1" value="{{ $settings['renewal_duration_days'] ?? 7 }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="block_on_overdue" value="1" id="block_on_overdue"
                        {{ ($settings['block_on_overdue'] ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 accent-[#1B2A5E]">
                    <label for="block_on_overdue" class="text-sm text-gray-700">
                        Blokir peminjaman baru jika anggota memiliki buku yang terlambat dikembalikan
                    </label>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-[#1B2A5E] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Simpan Aturan
                    </button>
                </div>
            </form>
        </div>

        {{-- DENDA --}}
        <div id="denda" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-1">💰 Denda & Sanksi Keterlambatan</h2>
            <p class="text-xs text-gray-400 mb-5">Tentukan besaran denda bagi anggota yang terlambat mengembalikan buku.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="section" value="denda">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Denda per Hari Keterlambatan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="fine_per_day" min="0" step="500" value="{{ $settings['fine_per_day'] ?? 1000 }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Maksimal Denda per Buku</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 text-sm">Rp</span>
                            <input type="number" name="max_fine_per_book" min="0" step="1000" value="{{ $settings['max_fine_per_book'] ?? 50000 }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Batas Hari Terlambat Sebelum Suspensi Akun</label>
                        <input type="number" name="suspension_threshold_days" min="0" value="{{ $settings['suspension_threshold_days'] ?? 30 }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                        <p class="text-xs text-gray-400 mt-1">Akun anggota akan otomatis disuspen jika melewati batas ini.</p>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-[#1B2A5E] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Simpan Ketentuan Denda
                    </button>
                </div>
            </form>
        </div>

        {{-- NOTIFIKASI --}}
        <div id="notifikasi" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-1">🔔 Notifikasi</h2>
            <p class="text-xs text-gray-400 mb-5">Atur pengingat otomatis yang dikirim ke anggota.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <input type="hidden" name="section" value="notifikasi">

                <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Pengingat Sebelum Jatuh Tempo</p>
                        <p class="text-xs text-gray-400">Kirim notifikasi 2 hari sebelum tenggat pengembalian.</p>
                    </div>
                    <input type="checkbox" name="notify_due_soon" value="1" {{ ($settings['notify_due_soon'] ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#1B2A5E]">
                </label>
                <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Notifikasi Keterlambatan</p>
                        <p class="text-xs text-gray-400">Kirim notifikasi saat buku terdeteksi terlambat.</p>
                    </div>
                    <input type="checkbox" name="notify_overdue" value="1" {{ ($settings['notify_overdue'] ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#1B2A5E]">
                </label>
                <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Notifikasi Buku Tersedia (Wishlist)</p>
                        <p class="text-xs text-gray-400">Beri tahu anggota saat buku di wishlist mereka tersedia kembali.</p>
                    </div>
                    <input type="checkbox" name="notify_wishlist_available" value="1" {{ ($settings['notify_wishlist_available'] ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#1B2A5E]">
                </label>

                <div class="pt-3 border-t border-gray-100">
                    <button type="submit" class="bg-[#1B2A5E] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Simpan Notifikasi
                    </button>
                </div>
            </form>
        </div>

        {{-- AKUN & KEAMANAN --}}
        <div id="akun" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-1">🔐 Akun & Keamanan</h2>
            <p class="text-xs text-gray-400 mb-5">Perbarui informasi akun admin dan kata sandi Anda.</p>

            <form action="{{ route('admin.settings.account') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nama</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>
                <hr class="border-gray-100">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Kata Sandi Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-[#1B2A5E] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Perbarui Akun
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection