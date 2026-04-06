@extends('layouts.kasir')

@section('title', 'Detail Order')

@section('content')

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header Navigation -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('kasir.orders.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
            </a>
            <div class="text-right">
                <p class="text-sm text-gray-600">Order ID: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Main Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Detail Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-gray-600 mt-2">Kelola dan konfirmasi pesanan dari customer</p>
        </div>

        <!-- Info Grid: Customer & Pengiriman -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Customer Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 ml-3">Customer</h3>
                </div>
                <div class="space-y-3 ml-16">
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-semibold text-gray-900">{{ $order->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-map-pin text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 ml-3">Alamat Pengiriman</h3>
                </div>
                <p class="text-gray-800 leading-relaxed ml-16">{{ $order->address ?? 'Belum tersedia' }}</p>
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fa-solid fa-box mr-3 text-lg"></i>Detail Produk Pesanan
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700">Produk</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Harga</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Qty</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-800 font-medium">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold text-sm">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-purple-600">
                                Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="px-6 py-4 text-center text-gray-500 col-span-4">Belum ada item</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Total Summary -->
            <div class="px-6 py-6 bg-gradient-to-r from-purple-50 to-blue-50 border-t-2 border-purple-200 flex justify-end">
                <div class="w-full md:w-1/3">
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal Produk:</span>
                            <span class="font-semibold">Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Ongkos Kirim:</span>
                            <span class="font-semibold">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t-2 border-purple-300 pt-2 flex justify-between text-lg">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-purple-600 text-2xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500">
                <h3 class="text-lg font-bold text-black flex items-center">
                    <i class="fa-solid fa-credit-card mr-3"></i>Status & Konfirmasi Pembayaran
                </h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- Payment Method -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-wallet text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 font-semibold">Metode Pembayaran</p>
                        <p class="text-lg text-gray-900 font-semibold">
                            @if($order->payment_method === 'transfer')
                                <i class="fa-solid fa-bank mr-2"></i>Transfer Bank
                            @else
                                {{ ucfirst($order->payment_method ?? 'Belum ditentukan') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                        @if($order->payment_status === 'pending') bg-orange-100
                        @elseif($order->payment_status === 'confirmed') bg-green-100
                        @else bg-red-100 @endif">
                        <i class="fa-solid 
                            @if($order->payment_status === 'pending') fa-hourglass-end text-orange-600
                            @elseif($order->payment_status === 'confirmed') fa-check-circle text-green-600
                            @else fa-times-circle text-red-600 @endif"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 font-semibold">Status Pembayaran</p>
                        <div class="mt-1">
                            @if($order->payment_status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-100 text-orange-800">
                                    <i class="fa-solid fa-hourglass-end mr-2"></i>Menunggu Pembayaran
                                </span>
                            @elseif($order->payment_status === 'confirmed')
                                <div class="space-y-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check-circle mr-2"></i>Pembayaran Dikonfirmasi
                                    </span>
                                    @if($order->payment_confirmation_date)
                                    <p class="text-xs text-gray-600 ml-1">
                                        Dikonfirmasi: {{ is_string($order->payment_confirmation_date) ? \Carbon\Carbon::parse($order->payment_confirmation_date)->format('d M Y H:i') : $order->payment_confirmation_date->format('d M Y H:i') }}
                                    </p>
                                    @endif
                                </div>
                            @elseif($order->payment_status === 'rejected')
                                <div class="space-y-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-times-circle mr-2"></i>Pembayaran Ditolak
                                    </span>
                                    @if($order->payment_notes)
                                    <p class="text-xs text-red-600 italic">Alasan: {{ $order->payment_notes }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (Only if pending) -->
                @if($order->payment_status === 'pending')
                <div class="pt-4 border-t-2 border-gray-100">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Ambil Aksi Pembayaran:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Approve Payment -->
                        <form method="POST" action="{{ route('kasir.orders.payment.approve', $order->id) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-black rounded-lg font-bold transition flex items-center justify-center">
                                <i class="fa-solid fa-check-circle mr-2"></i>Konfirmasi Pembayaran
                            </button>
                        </form>

                        <!-- Reject Payment -->
                        <button type="button" onclick="document.getElementById('rejectForm').classList.remove('hidden'); this.style.display='none'" 
                                class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition flex items-center justify-center" id="rejectBtn">
                            <i class="fa-solid fa-times-circle mr-2"></i>Tolak Pembayaran
                        </button>
                    </div>

                    <!-- Rejection Form -->
                    <div id="rejectForm" class="hidden mt-4 p-4 bg-red-50 border-2 border-red-200 rounded-lg">
                        <form method="POST" action="{{ route('kasir.orders.payment.reject', $order->id) }}" class="space-y-3">
                            @csrf
                            
                            <label for="payment_notes" class="block text-gray-800 font-bold text-sm">Alasan Penolakan Pembayaran:</label>
                            <textarea name="payment_notes" id="payment_notes" 
                                      class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:border-red-500 bg-white" 
                                      rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                            
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition">
                                    <i class="fa-solid fa-check mr-2"></i>Tolak Pembayaran
                                </button>
                                <button type="button" onclick="document.getElementById('rejectForm').classList.add('hidden'); document.getElementById('rejectBtn').style.display='flex'" 
                                        class="flex-1 px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-bold transition">
                                    <i class="fa-solid fa-times mr-2"></i>Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Status Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <h3 class="text-lg font-bold text-black flex items-center">
                    <i class="fa-solid fa-tasks mr-3"></i>Update Status Pesanan
                </h3>
            </div>

            <form action="{{ route('kasir.orders.update-status', $order->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <!-- Current Status -->
                <div class="flex items-center space-x-4">
                    <div class="w-32">
                        <p class="text-sm text-gray-600 font-semibold">Status Saat Ini:</p>
                    </div>
                    <div class="flex-1">
                        @if($order->status === 'pending')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">
                                <i class="fa-solid fa-hourglass-half mr-2"></i>Menunggu
                            </span>
                        @elseif($order->status === 'processing')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                <i class="fa-solid fa-gear mr-2"></i>Diproses
                            </span>
                        @elseif($order->status === 'shipped')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-purple-100 text-purple-800">
                                <i class="fa-solid fa-truck mr-2"></i>Dikirim
                            </span>
                        @elseif($order->status === 'delivered')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                <i class="fa-solid fa-check-circle mr-2"></i>Terima
                            </span>
                        @elseif($order->status === 'cancelled')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                <i class="fa-solid fa-times-circle mr-2"></i>Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Change Status -->
                <div class="border-t-2 border-gray-100 pt-6">
                    <label for="status" class="block text-gray-800 font-bold text-sm mb-3">Ubah Status ke:</label>
                    <select name="status" id="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 font-semibold">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>⚙️ Diproses</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Dikirim</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>✅ Terima</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-gray rounded-lg font-bold transition flex items-center justify-center">
                        <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('kasir.orders.index') }}" class="flex-1 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-bold transition flex items-center justify-center">
                        <i class="fa-solid fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
