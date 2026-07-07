{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — PerpusKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- TOP NAV --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 h-16">
            <div class="flex items-center gap-3">
                <a href="{{ route('catalog.index') }}" class="text-[#1B2A5E] font-bold text-xl flex items-center gap-2">
                    📚 PerpusKu
                    <span class="text-xs bg-[#1B2A5E] text-white px-2 py-0.5 rounded-full">ADMIN</span>
                </a>
                <span class="text-gray-300 mx-2">|</span>
                <span class="text-sm font-semibold text-gray-600">Dasbor Admin</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
                    <input placeholder="Cari sumber daya..." class="pl-8 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-52 focus:outline-none focus:ring-1 focus:ring-[#1B2A5E]">
                </div>
                <button class="text-gray-500 hover:text-[#1B2A5E] p-2">🔔</button>
                <div class="relative group">
                    <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                        <div class="w-8 h-8 bg-[#1B2A5E] rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>
                    <div class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-xl shadow-lg hidden group-hover:block py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-500">Administrator</p>
                            <p class="text-sm text-gray-700 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                🚪 Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1">
        {{-- SIDEBAR --}}
        <aside class="w-56 bg-white border-r border-gray-200 min-h-screen flex-shrink-0">
            <nav class="p-4 space-y-1 sticky top-16">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#1B2A5E] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    🏠 Ringkasan
                </a>

                <hr class="my-2 border-gray-200">

                <a href="{{ route('admin.books') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.books*') ? 'bg-[#1B2A5E] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    📚 Inventaris
                </a>
                <a href="{{ route('admin.loans') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.loans*') ? 'bg-[#1B2A5E] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    📋 Peminjaman
                </a>
                <a href="{{ route('admin.members') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.members*') ? 'bg-[#1B2A5E] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    👥 Anggota
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                    📊 Laporan
                </a>
            </nav>
        </aside>

        {{-- CONTENT --}}
        <main class="flex-1 p-8">
            @if(session('success') || session('error'))
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex justify-between items-center mb-4">
                ✅ {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="text-green-600 ml-4">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex justify-between items-center mb-4">
                ⚠️ {{ session('error') }}
                <button onclick="this.parentElement.remove()" class="text-red-600 ml-4">✕</button>
            </div>
            @endif
            @endif

            @yield('content')
        </main>
    </div>

    <footer class="bg-gray-800 text-gray-400 text-center text-xs py-4">
        © 2024 Sistem Manajemen Perpustakaan PerpusKu. Hak cipta dilindungi.
    </footer>

    @stack('scripts')
</body>

</html>
