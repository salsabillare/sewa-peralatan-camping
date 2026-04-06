@extends('layouts.frontend')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500 mb-4">
        <a href="{{ route('welcome') }}" class="hover:text-purple-600">CampGear Hub</a>
        <span class="mx-2">></span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 rounded-lg shadow">

        {{-- IMAGE --}}
        <div class="flex justify-center">
            <img src="{{ asset('storage/'.$product->image) }}"
                 class="w-full max-w-md object-contain border rounded-lg">
        </div>

        {{-- INFO --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                {{ $product->name }}
            </h1>

            {{-- PRICE --}}
            <div class="bg-purple-50 p-4 rounded-lg mb-4">
                <span class="text-3xl font-bold text-purple-700">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            {{-- STOCK --}}
            <p class="text-gray-600 mb-4">
                Stok: <span class="font-semibold">{{ $product->stock }}</span>
            </p>

            {{-- DESCRIPTION --}}
            <div class="mb-6">
                <h3 class="font-semibold mb-2">Deskripsi Produk</h3>
                <p class="text-gray-600">
                    {{ $product->description ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>

            {{-- ACTION --}}
            <div class="flex items-center gap-4">

                {{-- QTY --}}
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kuantitas</p>
                    <input type="number"
                           id="qty"
                           value="1"
                           min="1"
                           max="{{ $product->stock }}"
                           class="w-20 border rounded px-2 py-2">
                </div>

                {{-- ADD TO CART BUTTON --}}
                @if(auth()->check())
                    <form id="cartForm" action="{{ route('cart.add') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="cartQty" value="1">
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-3 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition">
                            <i class="fa fa-cart-plus"></i>
                            Masukkan Keranjang
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2 px-5 py-3 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition">
                        <i class="fa fa-cart-plus"></i>
                        Masukkan Keranjang
                    </a>
                @endif

                {{-- BUY NOW BUTTON --}}
                @if(auth()->check())
                    <form id="buyForm" action="{{ route('cart.add') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="buyQty" value="1">
                        <input type="hidden" name="redirect_to_checkout" value="1">
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Beli Sekarang
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Beli Sekarang
                    </a>
                @endif
            </div>

            {{-- Sync quantity with hidden inputs --}}
            <script>
                const qtyInput = document.getElementById('qty');
                const cartQtyInput = document.getElementById('cartQty');
                const buyQtyInput = document.getElementById('buyQty');

                qtyInput.addEventListener('input', function() {
                    cartQtyInput.value = this.value;
                    buyQtyInput.value = this.value;
                });
            </script>

        </div>
    </div>
</div>
@endsection
