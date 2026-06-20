{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun — PerpusKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#EEF2FF] to-[#DBEAFE] flex flex-col">

<header class="p-4 flex justify-between items-center">
    <a href="{{ route('catalog.index') }}" class="text-[#1B2A5E] font-bold text-xl">📚 PerpusKu</a>
    <a href="{{ route('login') }}" class="bg-[#1B2A5E] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#0F1D45]">Masuk</a>
</header>

<div class="flex-1 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-gray-900 text-center">Buat Akun</h1>
        <p class="text-gray-500 text-sm text-center mt-2 mb-6">Bergabunglah dengan komunitas perpustakaan kami untuk menjelajahi ribuan sumber daya.</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-4">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">👤</span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe"
                        class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 @error('name') border-red-400 @enderror">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">✉️</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com"
                        class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 @error('email') border-red-400 @enderror">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">ID Mahasiswa/Anggota</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">🪪</span>
                    <input type="text" name="member_id" value="{{ old('member_id') }}" placeholder="ID-12345678"
                        class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 @error('member_id') border-red-400 @enderror">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">🔒</span>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 @error('password') border-red-400 @enderror">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Konfirmasi</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">🔐</span>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-3 pt-1">
                <input type="checkbox" name="terms" id="terms" value="1"
                    class="mt-1 w-4 h-4 accent-[#1B2A5E] @error('terms') ring-2 ring-red-400 @enderror">
                <label for="terms" class="text-sm text-gray-600">
                    Saya setuju dengan <a href="#" class="text-[#1B2A5E] font-medium hover:underline">Syarat Layanan</a>
                    dan <a href="#" class="text-[#1B2A5E] font-medium hover:underline">Kebijakan Privasi</a> PerpusKu.
                </label>
            </div>

            <button type="submit"
                class="w-full bg-[#1B2A5E] text-white py-3 rounded-xl font-semibold hover:bg-[#0F1D45] transition flex items-center justify-center gap-2 mt-2">
                Daftar →
            </button>

            <p class="text-center text-sm text-gray-500 pt-2">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#1B2A5E] font-medium hover:underline">Masuk di sini</a>
            </p>
        </form>
    </div>
</div>

<footer class="bg-gray-800 text-gray-400 py-6 mt-6">
    <div class="max-w-6xl mx-auto px-8 grid grid-cols-4 gap-8 text-sm">
        <div>
            <p class="text-white font-semibold mb-2">PerpusKu</p>
            <p>Mendigitalkan warisan pengetahuan melalui aksesibilitas modern dan sistem arsip yang andal.</p>
        </div>
        <div>
            <p class="text-white font-semibold mb-2 uppercase text-xs tracking-wider">Sumber Daya</p>
            <ul class="space-y-1"><li><a href="#" class="hover:text-white">Peraturan Perpustakaan</a></li><li><a href="#" class="hover:text-white">Peta Situs</a></li><li><a href="#" class="hover:text-white">Pusat Bantuan</a></li></ul>
        </div>
        <div>
            <p class="text-white font-semibold mb-2 uppercase text-xs tracking-wider">Legal</p>
            <ul class="space-y-1"><li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li><li><a href="#" class="hover:text-white">Syarat Layanan</a></li></ul>
        </div>
        <div>
            <p class="text-white font-semibold mb-2 uppercase text-xs tracking-wider">Kontak</p>
            <p>Hubungi Kami</p><p>support@perpusku.edu</p>
        </div>
    </div>
    <div class="text-center mt-6 text-xs">© 2024 Sistem Manajemen Perpustakaan PerpusKu. Hak cipta dilindungi.</div>
</footer>
</body>
</html>