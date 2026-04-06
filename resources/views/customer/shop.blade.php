@extends('layouts.customer')

@section('content')
<!-- ================= BANNER ================= -->
<section class="max-w-7xl mx-auto px-4 mt-6">
    <div class="bg-gradient-to-r from-green-300 to-green-400 rounded-xl p-8 text-white">
        <h2 class="text-2xl font-bold mb-2">Sewa Peralatan Camping di Bantul 🏕</h2>
        <p>Mudah, terjangkau, dan berkualitas</p>
    </div>
</section>

<!-- ================= KATEGORI ================= -->
<section class="max-w-7xl mx-auto px-4 mt-8">
    <h3 class="font-bold text-green-800 mb-4">Kategori</h3>

    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-4 text-center">
        @foreach ($categories as $category)
            <a href="{{ route('shop.index', ['category' => $category->id]) }}"
               class="bg-white rounded-lg shadow p-3 hover:shadow-md cursor-pointer">

                <div class="w-12 h-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-2">
                    <i class="fa-solid fa-box text-green-600"></i>
                </div>

                <p class="text-sm text-green-700">
                    {{ $category->name }}
                </p>
            </a>
        @endforeach
    </div>
</section>

<!-- ================= PRODUK ================= -->
<section class="max-w-7xl mx-auto px-4 mt-10 mb-10">
    <h3 class="font-bold text-green-800 mb-4">Produk</h3>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach ($products as $product)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">

            <a href="{{ route('shop.show', $product->id) }}">
                <img src="{{ asset('storage/'.$product->image) }}"
                     class="w-full h-40 object-contain bg-green-50 p-4">
            </a>

            <div class="p-3">
                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2">
                    {{ $product->name }}
                </h4>

                <p class="text-green-600 font-bold mt-1">
                    Rp {{ number_format($product->price,0,',','.') }} /24jam
                </p>

                <!-- FORM TAMBAH KERANJANG -->
                <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-2">
                        <input type="number" name="quantity" value="1" min="1" 
                               class="w-12 px-2 py-1 border border-green-300 rounded text-center text-sm">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm py-1 rounded transition">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection
