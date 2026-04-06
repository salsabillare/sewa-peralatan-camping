@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Input Nomor Resi Pengiriman</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <!-- Detail Order -->
            <div class="mb-6 p-4 bg-gray-50 rounded">
                <h2 class="text-lg font-bold mb-4">Detail Pesanan</h2>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Order ID:</span>
                        <p class="font-semibold">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Customer:</span>
                        <p class="font-semibold">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Metode Pengiriman:</span>
                        <p class="font-semibold">{{ $order->shipping->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Estimasi Sampai:</span>
                        <p class="font-semibold">{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Input Tracking Number -->
            <form action="{{ route('kasir.orders.updateTracking', $order->id) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fa-solid fa-barcode mr-2"></i>Nomor Resi Pengiriman
                    </label>
                    <input type="text" name="tracking_number" 
                           class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-blue-600"
                           placeholder="Contoh: 1234567890ABC"
                           required>
                    <p class="text-xs text-gray-500 mt-2">Masukkan nomor resi dari kurir pengiriman</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-purple font-bold py-3 px-4 rounded transition">
                        <i class="fa-solid fa-check mr-2"></i>Kirim Pesanan
                    </button>
                    <a href="{{ route('kasir.orders.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-purple font-bold py-3 px-4 rounded transition text-center">
                        <i class="fa-solid fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
