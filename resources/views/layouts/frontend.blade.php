<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - CampGear Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

    {{-- NAVBAR SHOPEE STYLE --}}
    <header class="bg-gradient-to-r from-green-300 to-green-400 text-white">
    <div class="max-w-7xl mx-auto flex items-center gap-4 px-4 py-3">

        {{-- LOGO --}}
        <a href="{{ route('welcome') }}" class="text-2xl font-bold">
            🏕 CampGear Hub
        </a>

        {{-- SEARCH --}}
        <form action="{{ route('welcome') }}" method="GET" class="flex-1">
            <div class="flex">
                <input type="text"
                       name="search"
                       placeholder="Cari peralatan camping..."
                       class="w-full px-4 py-2 text-black rounded-l focus:outline-none">
                <button class="bg-yellow-400 text-gray-800 px-4 rounded-r hover:bg-yellow-500">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        {{-- CART --}}
        <a href="{{ auth()->check() ? route('shop.index') : route('login') }}"
           class="relative text-xl">
            <i class="fa fa-shopping-cart"></i>
        </a>

        {{-- AUTH AREA --}}
@guest
    <a href="{{ route('login') }}" class="ml-4 hover:underline">Login</a>
    <a href="{{ route('register') }}" class="ml-2 hover:underline">Daftar</a>
@else
    <div class="relative group ml-4">

        {{-- USERNAME --}}
        <button class="flex items-center gap-2 bg-yellow-400 text-gray-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-yellow-500">
            <i class="fa fa-user"></i>
            {{ auth()->user()->name }}
        </button>

        {{-- DROPDOWN --}}
        <div class="absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded shadow-lg
                    opacity-0 group-hover:opacity-100 invisible group-hover:visible transition">

            <a href="{{ route('customer.orders.index') }}"
               class="block px-4 py-2 hover:bg-green-100 text-gray-700">
                Pesanan Saya
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left px-4 py-2 hover:bg-green-100 text-gray-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endguest

    </div>
</header>

    {{-- CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
