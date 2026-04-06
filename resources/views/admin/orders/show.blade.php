@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-purple-600 hover:text-purple-800">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Detail Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>

    <!-- Info Customer dan Pengiriman -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-purple-600 mb-4">
                <i class="fa-solid fa-user mr-2"></i>Informasi Customer
            </h3>
            <div class="space-y-2">
                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Telepon:</strong> {{ $order->user->phone ?? '-' }}</p>
                <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Alamat Pengiriman -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-purple-600 mb-4">
                <i class="fa-solid fa-map-pin mr-2"></i>Alamat Pengiriman
            </h3>
            <p class="text-gray-800">{{ $order->address ?? 'Belum tersedia' }}</p>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-purple-100 border-b">
            <h3 class="text-lg font-bold text-purple-800">
                <i class="fa-solid fa-box mr-2"></i>Detail Produk
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-gray-700">
                        <th class="px-6 py-3 text-left font-bold">Produk</th>
                        <th class="px-6 py-3 text-center font-bold">Harga</th>
                        <th class="px-6 py-3 text-center font-bold">Jumlah</th>
                        <th class="px-6 py-3 text-right font-bold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product->sku ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-800">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center text-gray-800">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-gray-500" colspan="4">
                            Belum ada item dalam pesanan ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="px-6 py-4 bg-gray-50 border-t text-right">
            <div class="flex justify-end">
                <div class="w-1/3">
                    <p class="text-lg"><strong>Total Pesanan:</strong></p>
                    <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Ongkos Kirim (sudah dipilih customer saat checkout) -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-green-600 mb-4">
            <i class="fa-solid fa-check-circle mr-2"></i>Informasi Pengiriman
        </h3>
        
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-700">Ongkos Kirim:</span>
                <span class="font-semibold text-lg text-green-600">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="font-bold">Total Pembayaran:</span>
                <span class="font-bold text-lg text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            <p class="text-sm text-green-700 mt-3">
                <i class="fa-solid fa-info-circle mr-1"></i>
                Ongkos kirim telah dipilih oleh customer saat melakukan checkout. Siap untuk diproses.
            </p>
        </div>
    </div>

    <!-- Status Pembayaran & Approval -->
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-orange-600 mb-4">
            <i class="fa-solid fa-credit-card mr-2"></i>Status Pembayaran
        </h3>
        
        <div class="space-y-4">
            <!-- Metode Pembayaran -->
            <div>
                <p class="text-gray-700 font-semibold mb-1">Metode Pembayaran:</p>
                <p class="text-gray-800">
                    @if($order->payment_method === 'transfer')
                        <i class="fa-solid fa-bank text-orange-600 mr-2"></i>Transfer Bank
                    @else
                        {{ ucfirst($order->payment_method ?? 'Belum ditentukan') }}
                    @endif
                </p>
            </div>

            <!-- Status Pembayaran Saat Ini -->
            <div>
                <p class="text-gray-700 font-semibold mb-2">Status Saat Ini:</p>
                @if($order->payment_status === 'pending')
                    <span class="inline-block bg-orange-100 text-orange-800 px-4 py-2 rounded-lg font-semibold">
                        <i class="fa-solid fa-hourglass-end mr-1"></i>Menunggu Pembayaran
                    </span>
                @elseif($order->payment_status === 'confirmed')
                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                        <i class="fa-solid fa-check-circle mr-1"></i>Pembayaran Dikonfirmasi
                    </span>
                    @if($order->payment_confirmation_date)
                    <p class="text-sm text-gray-600 mt-1">
                        Dikonfirmasi pada: {{ is_string($order->payment_confirmation_date) ? \Carbon\Carbon::parse($order->payment_confirmation_date)->format('d M Y H:i') : $order->payment_confirmation_date->format('d M Y H:i') }}
                    </p>
                    @endif
                @elseif($order->payment_status === 'rejected')
                    <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                        <i class="fa-solid fa-times-circle mr-1"></i>Pembayaran Ditolak
                    </span>
                    @if($order->payment_notes)
                    <p class="text-sm text-red-600 mt-1">Alasan: {{ $order->payment_notes }}</p>
                    @endif
                @endif
            </div>

            <!-- Formulir Approval/Rejection -->
            @if($order->payment_status === 'pending')
            <div class="mt-4 space-y-3 pt-4 border-t">
                <p class="text-sm font-semibold text-gray-700 mb-3">Aksi Pembayaran:</p>
                
                <!-- Debug Info -->
                <div class="text-xs text-gray-500 mb-2">
                    <p>Form Action: {{ route('orders.payment.approve', $order->id) }}</p>
                    <p>Order ID: {{ $order->id }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('orders.payment.approve', $order->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black rounded-lg font-semibold transition">
                            <i class="fa-solid fa-check-circle mr-2"></i>Konfirmasi Pembayaran
                        </button>
                    </form>

                    <!-- Reject Button - Opens Form -->
                    <button type="button" onclick="document.getElementById('rejectForm').style.display='block'; document.getElementById('rejectBtn').style.display='none'" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition" id="rejectBtn">
                        <i class="fa-solid fa-times-circle mr-2"></i>Tolak Pembayaran
                    </button>
                </div>
            </div>

            <!-- Form Penolakan (Hidden) -->
            <div id="rejectForm" class="hidden mt-4 p-4 bg-red-100 border border-red-300 rounded-lg">
                <form method="POST" action="{{ route('orders.payment.reject', $order->id) }}" class="space-y-3">
                    @csrf
                    
                    <label for="payment_notes" class="block text-gray-700 font-semibold">Alasan Penolakan:</label>
                    <textarea name="payment_notes" id="payment_notes" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-600" 
                              rows="3" required></textarea>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                            <i class="fa-solid fa-check mr-2"></i>Tolak
                        </button>
                        <button type="button" onclick="document.getElementById('rejectForm').style.display='none'; document.getElementById('rejectBtn').style.display='block'" 
                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg font-semibold">
                            <i class="fa-solid fa-times mr-2"></i>Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Status Update Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 mb-6">
        <h3 class="text-lg font-bold text-purple-600 mb-4">
            <i class="fa-solid fa-edit mr-2"></i>Update Status Pesanan
        </h3>
        
        <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status Saat Ini:</label>
                <div class="inline-block">
                    @if($order->status === 'pending')
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-hourglass-half mr-1"></i>Menunggu
                        </span>
                    @elseif($order->status === 'processing')
                        <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-gear mr-1"></i>Diproses
                        </span>
                    @elseif($order->status === 'shipped')
                        <span class="inline-block bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-truck mr-1"></i>Dikirim
                        </span>
                    @elseif($order->status === 'delivered')
                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-check-circle mr-1"></i>Terima
                        </span>
                    @elseif($order->status === 'cancelled')
                        <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-times-circle mr-1"></i>Dibatalkan
                        </span>
                    @else
                        <span class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                            {{ ucfirst($order->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-2">Ubah Status:</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Terima</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('orders.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Delete -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-red-600 mb-4">
            <i class="fa-solid fa-trash mr-2"></i>Hapus Pesanan
        </h3>
        <p class="text-red-700 mb-4">Tindakan ini tidak dapat dibatalkan. Pesanan dan semua itemnya akan dihapus secara permanen.</p>
        
        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                <i class="fa-solid fa-trash mr-2"></i>Hapus Pesanan
            </button>
        </form>
    </div>
</div>

@endsection
