@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Daftar Produk -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left">Produk</th>
                                <th class="px-6 py-3 text-center">Harga</th>
                                <th class="px-6 py-3 text-center">Jumlah</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-16 h-16 object-cover rounded mr-4">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded mr-4"></div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">SKU: {{ $item->product->sku ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                               min="1" class="w-16 px-2 py-1 border rounded text-center" 
                                               onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" 
                                                onclick="return confirm('Hapus produk ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>Rp {{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('checkout.index') }}" method="GET">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Checkout
                        </button>
                    </form>

                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Kosongkan keranjang?')">
                            Kosongkan Keranjang
                        </button>
                    </form>

                    <a href="{{ route('shop.index') }}" class="block text-center mt-2 text-blue-600 hover:text-blue-800">
                        Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <i class="fa-solid fa-cart-shopping text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-600 mb-4">Keranjang Belanja Kosong</h2>
            <p class="text-gray-500 mb-8">Anda belum menambahkan produk ke keranjang</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection
