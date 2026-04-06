<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampGear Hub - @yield('title')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f7fa;
        }
        
        header {
            background: linear-gradient(135deg, #A5D6A7 0%, #81C784 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">

        <!-- LOGO -->
        <a href="{{ route('welcome') }}" class="text-white font-bold text-2xl flex items-center gap-2">
            <span class="text-3xl">⛺</span>
            CampGear Hub
        </a>

        <!-- SEARCH -->
        <form action="{{ route('shop.index') }}" method="GET" class="flex-1 flex">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari peralatan camping..."
                   class="w-full px-4 py-2 rounded-l focus:outline-none">
            <button class="bg-green-600 px-4 rounded-r text-white hover:bg-green-700">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <!-- ICON KANAN -->
        <div class="flex items-center gap-5 text-white">

            <!-- KERANJANG AKTIF -->
            <a href="{{ route('cart.index') }}" title="Keranjang" class="text-xl hover:text-yellow-200 transition">
                <i class="fa-solid fa-backpack"></i>
            </a>

            <!-- USER DROPDOWN -->
            <div class="relative group">
                <button class="flex items-center gap-2 text-sm hover:text-yellow-200 transition">
                    <span>{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-0 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-green-100 first:rounded-t-lg">
                        <i class="fa-solid fa-user mr-2 text-green-700"></i>Profil Saya
                    </a>
                    <a href="{{ route('cart.index') }}" class="block px-4 py-2 hover:bg-green-100">
                        <i class="fa-solid fa-backpack mr-2 text-green-700"></i>Keranjang
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 hover:bg-green-100">
                        <i class="fa-solid fa-box mr-2 text-green-700"></i>Pesanan Saya
                    </a>
                    <hr class="my-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-100 text-red-600 rounded-b-lg">
                            <i class="fa-solid fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ================= CONTENT ================= -->
@yield('content')

</body>
</html>
