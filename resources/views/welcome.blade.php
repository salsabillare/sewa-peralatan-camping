<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampGear Hub - Sewa Peralatan Camping</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome CSS (AMAN & PASTI MUNCUL) -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background-color: #f5f7fa;
        }
        
        header {
            background: linear-gradient(135deg, #8BC34A 0%, #558B2F 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .banner {
            background: linear-gradient(135deg, #8BC34A 0%, #558B2F 100%);
            border-radius: 12px;
        }
        
        .category-icon {
            background: linear-gradient(135deg, #C5E1A5 0%, #AED581 100%);
        }
        
        .product-card {
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .price-tag {
            color: #8BC34A;
        }
    </style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-4">

        <!-- LOGO TOKO -->
        <a href="/" class="text-white font-bold text-2xl tracking-wide flex items-center gap-2">
            <span class="text-3xl">⛺</span>
            CampGear Hub
        </a>

        <!-- SEARCH -->
        <form action="{{ route('welcome') }}" method="GET" class="flex-1 flex">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari peralatan camping..."
                   class="w-full px-4 py-2 rounded-l focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <button class="bg-yellow-600 px-4 rounded-r text-white hover:bg-yellow-700">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <!-- ICON KANAN -->
        <div class="flex items-center gap-5 text-white text-xl">

            <!-- KERANJANG -->
            @auth
                <a href="{{ route('cart.index') }}" title="Keranjang" class="hover:text-yellow-200 transition">
                    <i class="fa-solid fa-backpack"></i>
                </a>
            @else
                <a href="{{ route('login') }}" title="Login" class="hover:text-yellow-200 transition">
                    <i class="fa-solid fa-backpack"></i>
                </a>
            @endauth

            <!-- USER -->
            @auth
                <a href="{{ url('/shop') }}" class="text-sm hover:underline">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-sm hover:underline">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="bg-white text-green-600 px-3 py-1 rounded text-sm font-semibold hover:bg-green-50">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- ================= BANNER ================= -->
<section class="max-w-7xl mx-auto px-4 mt-6">
    <div class="banner rounded-xl overflow-hidden shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Video Section -->
            <div class="relative w-full bg-black overflow-hidden">
                <video 
                    width="100%" 
                    height="400" 
                    controls
                    class="w-full h-full object-cover"
                    style="aspect-ratio: 16 / 9;">
                    <source src="{{ asset('storage/videos/hiking.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung video HTML5.
                </video>
            </div>
            
            <!-- Text Section -->
            <div class="p-8 text-white flex flex-col justify-center bg-gradient-to-br from-green-600 to-green-800">
                <h2 class="text-3xl font-bold mb-3">⛺ Sewa Peralatan Camping Berkualitas</h2>
                <p class="text-lg text-green-100 mb-4">Lengkap, terjangkau, dan siap untuk petualangan Anda!</p>
                <p class="text-green-200 text-sm">Dari tas ransel hingga peralatan outdoor premium, kami siap melengkapi setiap pendakian impian Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= KATEGORI ================= -->
<section class="max-w-7xl mx-auto px-4 mt-10">
    <h3 class="font-bold text-gray-800 mb-6 text-xl">📂 Kategori Peralatan</h3>

    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-4 text-center">
        @foreach ($categories as $category)
            <a href="{{ route('welcome', ['category' => $category->id]) }}"
               class="bg-white rounded-lg shadow p-3 hover:shadow-md cursor-pointer transition">

                <div class="w-12 h-12 mx-auto category-icon rounded-full flex items-center justify-center mb-2">
                    <i class="fa-solid fa-leaf text-green-700 text-lg"></i>
                </div>

                <p class="text-sm text-gray-700 font-medium">
                    {{ $category->name }}
                </p>
            </a>
        @endforeach
    </div>
</section>

<!-- ================= PRODUK ================= -->
<section class="max-w-7xl mx-auto px-4 mt-12 pb-10">
    <h3 class="font-bold text-gray-800 mb-6 text-xl">🏕️ Peralatan Sewa</h3>

    @if ($products->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-yellow-600 text-xl"></i>
                <div>
                    <p class="text-yellow-800 font-semibold">Tidak ada produk ditemukan</p>
                    <p class="text-yellow-700 text-sm">Coba ubah kata kunci pencarian atau filter kategori Anda</p>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($products as $product)
            <div class="product-card bg-white rounded-lg shadow overflow-hidden">

                <a href="{{ route('product.show', $product->id) }}">
                    <img src="{{ asset('storage/'.$product->image) }}"
                         class="w-full h-40 object-contain bg-green-50 p-4">
                </a>

                <div class="p-3">
                    <h4 class="text-sm font-semibold text-gray-800 line-clamp-2">
                        {{ $product->name }}
                    </h4>

                    <p class="price-tag font-bold mt-1 text-base">
                        Rp {{ number_format($product->price,0,',','.') }}/hari
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-gray-800 text-gray-300 py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Tentang -->
            <div>
                <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                    <span class="text-2xl">⛺</span> CampGear Hub
                </h4>
                <p class="text-sm text-gray-400">Toko sewa peralatan camping terpercaya dengan kualitas terbaik untuk mendukung petualangan outdoor Anda.</p>
            </div>

            <!-- Link Cepat -->
            <div>
                <h4 class="text-white font-bold mb-4">Link Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('welcome') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Toko</a></li>
                    @auth
                        <li><a href="{{ route('customer.orders.index') }}" class="hover:text-white transition">Pesanan Saya</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="text-white font-bold mb-4">Hubungi Kami</h4>
                <ul class="space-y-2 text-sm">
                    <li><i class="fa-solid fa-phone mr-2"></i>+62 882 3836 2615</li>
                    <li><i class="fa-solid fa-envelope mr-2"></i>info@campgearhub.com</li>
                    <li><i class="fa-solid fa-map-marker-alt mr-2"></i>Yogyakarta, Indonesia</li>
                </ul>
            </div>
        </div>

        <hr class="border-gray-700 mb-6">

        <!-- Copyright -->
        <div class="text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} <strong class="text-white">CampGear Hub</strong> - Sewa Peralatan Camping Terpercaya</p>
            <p class="mt-2 text-gray-500">Dikembangkan oleh <strong class="text-white">Salsabilla Fitri Choirunnisa</strong></p>
        </div>
    </div>
</footer>

</body>
</html>
