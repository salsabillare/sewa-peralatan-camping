@extends('layouts.customer')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 flex items-center">
        <i class="fa-solid fa-box mr-3 text-purple-600"></i>
        Pesanan Saya
    </h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow-md">
            <i class="fa-solid fa-box text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-6">Anda belum melakukan pemesanan. Mulai belanja sekarang!</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition">
                <i class="fa-solid fa-shopping-bag mr-2"></i>Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <!-- Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600 text-sm">Nomor Pesanan</p>
                        <p class="text-lg font-bold text-gray-800">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600 text-sm">Tanggal</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Items -->
                <div class="px-6 py-4">
                    <div class="space-y-3 mb-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="font-semibold">
                                @if($order->status === 'pending')
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-hourglass-half mr-1"></i>Menunggu
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-gear mr-1"></i>Diproses
                                    </span>
                                @elseif($order->status === 'shipped')
                                    <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-truck mr-1"></i>Dikirim
                                    </span>
                                @elseif($order->status === 'delivered')
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-check-circle mr-1"></i>Terima
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fa-solid fa-times-circle mr-1"></i>Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600 text-sm">Total</p>
                            <p class="text-xl font-bold text-purple-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
                            <i class="fa-solid fa-eye"></i>Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
