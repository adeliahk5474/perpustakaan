{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PerpusKu') — Sistem Manajemen Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#1B2A5E',
                            dark: '#0F1D45',
                            light: '#2D3E7B'
                        },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .status-available {
            @apply bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full;
        }

        .status-borrowed {
            @apply bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full;
        }

        .status-overdue {
            @apply bg-orange-100 text-orange-700 text-xs font-bold px-2 py-0.5 rounded-full;
        }

        .status-returned {
            @apply bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full;
        }

        .status-ebook {
            @apply bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 text-[#1B2A5E] font-bold text-xl">
                        <span class="text-2xl">📚</span> PerpusKu
                        @auth @if(auth()->user()->isAdmin())
                        <span class="text-xs bg-[#1B2A5E] text-white px-2 py-0.5 rounded-full font-medium">ADMIN</span>
                        @endif @endauth
                    </a>

                    {{-- Nav Links --}}
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('catalog.index') }}" class="text-sm font-medium {{ request()->routeIs('catalog.*') && !request()->routeIs('member.*') ? 'text-[#1B2A5E] border-b-2 border-[#1B2A5E] pb-0.5' : 'text-gray-600 hover:text-[#1B2A5E]' }}">
                            Beranda/Katalog
                        </a>
                        @auth @if(auth()->user()->isMember())
                        <a href="{{ route('member.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('member.*') ? 'text-[#1B2A5E] border-b-2 border-[#1B2A5E] pb-0.5' : 'text-gray-600 hover:text-[#1B2A5E]' }}">
                            Buku Saya
                        </a>
                        @endif @endauth
                        @auth @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('admin.*') ? 'text-[#1B2A5E] border-b-2 border-[#1B2A5E] pb-0.5' : 'text-gray-600 hover:text-[#1B2A5E]' }}">
                            Dasbor Admin
                        </a>
                        @endif @endauth
                    </div>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    <form action="{{ route('catalog.index') }}" method="GET" class="hidden md:block">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sumber daya..."
                                class="pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg w-56 focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                            <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                        </div>
                    </form>

                    <button class="text-gray-500 hover:text-[#1B2A5E] p-2">🔔</button>

                    @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 bg-[#1B2A5E] rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-xl shadow-lg hidden group-hover:block py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs font-medium text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
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
                    @else
                    <a href="{{ route('login') }}" class="bg-[#1B2A5E] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-[#0F1D45] transition">
                        Masuk
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex justify-between items-center">
            ✅ {{ session('success') }}
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 ml-4">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex justify-between items-center">
            ⚠️ {{ session('error') }}
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 ml-4">✕</button>
        </div>
        @endif
    </div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-gray-400 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm">
                © 2024 Sistem Manajemen Perpustakaan PerpusKu. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
