<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>

    {{-- Vite / Assets --}}
    @if (app()->environment() === 'local' || true)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Lucide Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.245.0/dist/lucide.min.js"></script>

    <style>
        .card-scroll { max-height: 320px; overflow-y: auto; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r hidden md:block">
            <div class="p-6">
                <a href="{{ route('admin.dashboard') ?? url('/dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-300 to-green-500 flex items-center justify-center text-white font-bold">🏕️</div>
                    <div>
                        <div class="text-lg font-semibold text-green-800">{{ config('app.name', 'App') }}</div>
                        <div class="text-sm text-green-700">Admin Panel</div>
                    </div>
                </a>
            </div>

            <nav class="px-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') ?? url('/dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-green-100 {{ request()->routeIs('admin.dashboard') ? 'bg-green-100' : '' }}">
                            <i data-lucide="home" class="w-5 h-5 text-green-700"></i>
                            <span class="text-sm font-medium text-green-800">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-green-100 {{ request()->is('products*') ? 'bg-green-100' : '' }}">
                            <i data-lucide="package" class="w-5 h-5 text-green-700"></i>
                            <span class="text-sm font-medium text-green-800">Peralatan</span>
                        </a>
                    </li>

                    {{-- ✅ Ganti bagian Orders -> Transaksi --}}
                    <li>
                        <a href="{{ url('kasir/transactions') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-green-50 {{ request()->is('kasir/transactions*') ? 'bg-green-50' : '' }}">
    <i data-lucide="shopping-cart" class="w-5 h-5 text-green-600"></i>
    <span class="text-sm font-medium text-green-700">Transaksi</span>
</a>

                    </li>

                    <li>
                        <a href="{{ route('users.index') ?? url('/users') }}" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-green-50 {{ request()->is('users*') ? 'bg-green-50' : '' }}">
                            <i data-lucide="users" class="w-5 h-5 text-green-600"></i>
                            <span class="text-sm font-medium text-green-700">Manajemen User</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">

            <!-- NAVBAR -->
            <header class="bg-white border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-4">
                            <button id="sidebar-toggle" class="md:hidden p-2 rounded-md hover:bg-gray-100">
                                <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
                            </button>
                            <h2 class="text-xl font-semibold text-green-800">@yield('title', 'Dashboard')</h2>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden sm:flex items-center bg-gray-100 rounded-lg px-3 py-1">
                                <i data-lucide="search" class="w-4 h-4 text-gray-500"></i>
                                <input type="text" placeholder="Search..." class="bg-transparent ml-2 outline-none text-sm">
                            </div>

                            <div class="relative">
                                <button id="user-button" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
                                    <img src="{{ auth()->user()->profile_photo_url ?? 'https://via.placeholder.com/40' }}" class="w-8 h-8 rounded-full object-cover">
                                    <span class="hidden sm:inline text-sm text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                                </button>

                                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT WRAPPER -->
            <main class="p-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>

            <footer class="mt-auto bg-white border-t">
                <div class="max-w-7xl mx-auto p-4 text-sm text-gray-500">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.replace();

            const togg = document.getElementById('sidebar-toggle');
            togg && togg.addEventListener('click', () => {
                document.querySelector('aside').classList.toggle('hidden');
            });

            const userBtn = document.getElementById('user-button');
            const userMenu = document.getElementById('user-menu');
            userBtn && userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => userMenu && userMenu.classList.add('hidden'));
        });
    </script>

    @stack('scripts')
</body>
</html>
