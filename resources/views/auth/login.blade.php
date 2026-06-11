{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — PerpusKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#EEF2FF] to-[#DBEAFE] flex flex-col">

    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <a href="{{ route('catalog.index') }}" class="text-[#1B2A5E] font-bold text-2xl">📚 PerpusKu</a>
                <p class="text-gray-500 text-sm mt-1">Unified Library Access Management</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Sign In</h1>

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-4">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Simulation Mode Toggle (UI only) --}}
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider text-center mb-2">Login Simulation Mode</p>
                        <div class="flex rounded-lg bg-gray-100 p-1 gap-1">
                            <button type="button" id="btn-member"
                                onclick="setMode('member')"
                                class="flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 bg-[#1B2A5E] text-white">
                                👤 Member
                            </button>
                            <button type="button" id="btn-admin"
                                onclick="setMode('admin')"
                                class="flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 text-gray-600 hover:bg-white">
                                ⚙️ Admin
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Email or Username</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">👤</span>
                            <input type="text" name="email" id="email-input"
                                value="{{ old('email') }}"
                                placeholder="Enter your credentials"
                                class="w-full pl-9 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30 @error('email') border-red-400 @enderror">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Password</label>
                            <a href="#" class="text-xs text-[#1B2A5E] hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">🔒</span>
                            <input type="password" name="password" id="password-input"
                                placeholder="••••••••"
                                class="w-full pl-9 pr-12 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1B2A5E]/30">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                👁
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#1B2A5E] text-white py-3 rounded-xl font-semibold hover:bg-[#0F1D45] transition flex items-center justify-center gap-2">
                        Access Dashboard →
                    </button>

                    <p class="text-center text-sm text-gray-500">
                        Don't have an account yet?
                        <a href="{{ route('register') }}" class="text-[#1B2A5E] font-medium hover:underline">Apply for Library Membership</a>
                    </p>
                </form>
            </div>

            <div class="flex items-center justify-center gap-3 mt-4 text-sm text-gray-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    System Online
                </span>
                <span>•</span>
                <span>v2.4.0-stable</span>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-gray-400 py-4 text-center text-xs">
        <div class="flex justify-between px-8">
            <span>© 2024 PerpusKu Library Management System. All rights reserved.</span>
            <span class="space-x-4">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <a href="#" class="hover:text-white">Terms of Service</a>
                <a href="#" class="hover:text-white">Help Center</a>
                <a href="#" class="hover:text-white">Library Rules</a>
            </span>
        </div>
    </footer>

    <script>
        let mode = 'member';

        function setMode(m) {
            mode = m;
            const btnMember = document.getElementById('btn-member');
            const btnAdmin = document.getElementById('btn-admin');
            if (m === 'member') {
                btnMember.className = 'flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 bg-[#1B2A5E] text-white';
                btnAdmin.className = 'flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 text-gray-600 hover:bg-white';
                document.getElementById('email-input').placeholder = 'aryan@student.edu';
            } else {
                btnAdmin.className = 'flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 bg-[#1B2A5E] text-white';
                btnMember.className = 'flex-1 py-2 rounded-md text-sm font-medium transition flex items-center justify-center gap-1 text-gray-600 hover:bg-white';
                document.getElementById('email-input').placeholder = 'admin@perpusku.edu';
            }
        }

        function togglePassword() {
            const input = document.getElementById('password-input');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
