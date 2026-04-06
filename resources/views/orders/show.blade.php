@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-xl p-6 border border-yellow-200">
    <h1 class="text-2xl font-bold text-yellow-700 mb-6 flex items-center gap-2">
        <i class="fas fa-receipt text-yellow-600"></i> Detail Pesanan #{{ $order->id }}
    </h1>

    {{-- Informasi pelanggan dan pesanan --}}
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-yellow-700 mb-2">🧍‍♀️ Pelanggan</h2>
            <p class="text-gray-700"><strong>Nama:</strong> {{ $order->user->name }}</p>
            <p class="text-gray-700"><strong>Email:</strong> {{ $order->user->email }}</p>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-yellow-700 mb-2">📅 Informasi Pesanan</h2>
            <p class="text-gray-700"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            <p class="text-gray-700"><strong>Status:</strong>
                @if($order->status == 'pending')
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu</span>
                @elseif($order->status == 'success')
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
                @else
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst($order->status) }}</span>
                @endif
            </p>
            <p class="text-gray-700"><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>
    </div>

   {{-- Daftar produk dalam pesanan --}}
<h2 class="text-lg font-semibold text-yellow-700 mb-3">🛒 Daftar Produk</h2>
<div class="overflow-x-auto">
    <table class="w-full border border-yellow-200 rounded-lg overflow-hidden">
        <thead class="bg-yellow-200 text-yellow-800">
            <tr>
                <th class="py-3 px-4 text-left font-semibold">Produk</th>
                <th class="py-3 px-4 text-left font-semibold">Harga</th>
                <th class="py-3 px-4 text-left font-semibold">Jumlah</th>
                <th class="py-3 px-4 text-left font-semibold">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-b border-yellow-100 hover:bg-yellow-50 transition duration-150">
                <td class="py-3 px-4 flex items-center gap-3">
                    @if($item->product->image)
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded-md shadow-sm">
                    @else
                        <div class="w-12 h-12 bg-yellow-100 flex items-center justify-center rounded-md text-yellow-600 font-bold">
                            📦
                        </div>
                    @endif
                    <span class="text-gray-700">{{ $item->product->name }}</span>
                </td>
                <td class="py-3 px-4 text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="py-3 px-4 text-gray-700">{{ $item->quantity }}</td>
                <td class="py-3 px-4 text-yellow-700 font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


    {{-- Tombol kembali --}}
    <div class="mt-6 text-right">
        <a href="{{ route('orders.index') }}" 
           class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition inline-flex items-center gap-2">
           <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
