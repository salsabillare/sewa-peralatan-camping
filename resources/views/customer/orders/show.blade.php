@extends('layouts.customer')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Tombol Kembali -->
    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-2 mb-6 text-purple-600 hover:text-purple-800">
        <i class="fa-solid fa-arrow-left"></i>
        Kembali ke Pesanan
    </a>

    <!-- Notifikasi Status Pembayaran -->
    @if($order->payment_status === 'pending')
    <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-hourglass-end text-orange-600 mt-1"></i>
            <div>
                <p class="font-semibold text-orange-800">⏳ Menunggu Pembayaran dari Anda</p>
                <p class="text-sm text-orange-700 mt-1">Silakan transfer sesuai jumlah total pesanan. Lihat detail bank di bawah.</p>
            </div>
        </div>
    </div>
    @elseif($order->payment_status === 'confirmed')
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-check-circle text-green-600 mt-1"></i>
            <div>
                <p class="font-semibold text-green-800">✓ Pembayaran Berhasil Dikonfirmasi</p>
                <p class="text-sm text-green-700 mt-1">
                    Terima kasih! Admin telah mengkonfirmasi pembayaran Anda
                    @if($order->payment_confirmation_date)
                        pada {{ is_string($order->payment_confirmation_date) ? \Carbon\Carbon::parse($order->payment_confirmation_date)->format('d M Y H:i') : $order->payment_confirmation_date->format('d M Y H:i') }}
                    @endif
                    . Pesanan Anda akan segera diproses.
                </p>
            </div>
        </div>
    </div>
    @elseif($order->payment_status === 'rejected')
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-times-circle text-red-600 mt-1"></i>
            <div>
                <p class="font-semibold text-red-800">✗ Pembayaran Ditolak</p>
                <p class="text-sm text-red-700 mt-1">{{ $order->payment_notes ?? 'Hubungi admin untuk informasi lebih lanjut.' }}</p>
                <p class="text-sm text-red-700 mt-2">Silakan hubungi customer service untuk bantuan.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Pesanan -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-purple-400 to-purple-600 px-6 py-6">
            <h1 class="text-3xl font-bold text-white mb-2">
                Pesanan #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-purple-100">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
        </div>

        <!-- Info Pesanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b">
            <!-- Pemesan -->
            <div>
                <h3 class="font-bold text-gray-700 mb-2 flex items-center">
                    <i class="fa-solid fa-user text-purple-600 mr-2"></i>Informasi Pemesan
                </h3>
                <p class="text-gray-800"><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p class="text-gray-800"><strong>Email:</strong> {{ $order->user->email }}</p>
                <p class="text-gray-800"><strong>Telepon:</strong> {{ $order->user->phone ?? '-' }}</p>
            </div>

            <!-- Alamat Pengiriman -->
            <div>
                <h3 class="font-bold text-gray-700 mb-2 flex items-center">
                    <i class="fa-solid fa-map-pin text-purple-600 mr-2"></i>Alamat Pengiriman
                </h3>
                <p class="text-gray-800">{{ $order->address }}</p>
            </div>
        </div>

        <!-- Status & Metode -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-gray-50">
            <!-- Status -->
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-2">STATUS PESANAN</p>
                @if($order->status === 'pending')
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-hourglass-half mr-1"></i>Menunggu
                    </span>
                @elseif($order->status === 'diproses')
                    <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-gear mr-1"></i>Diproses
                    </span>
                @elseif($order->status === 'siap_dikirim')
                    <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-box mr-1"></i>Siap Dikirim
                    </span>
                @elseif($order->status === 'shipped')
                    <span class="inline-block bg-purple-100 text-purple-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-truck mr-1"></i>Dikirim
                    </span>
                @elseif($order->status === 'delivered')
                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-check-circle mr-1"></i>Sudah Diterima
                    </span>
                @elseif($order->status === 'cancelled')
                    <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-times-circle mr-1"></i>Dibatalkan
                    </span>
                @endif
            </div>

            <!-- Status Pembayaran (NEW) -->
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-2">STATUS PEMBAYARAN</p>
                @if($order->payment_status === 'pending')
                    <span class="inline-block bg-orange-100 text-orange-800 px-3 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-hourglass-half mr-1"></i>Menunggu
                    </span>
                @elseif($order->payment_status === 'confirmed')
                    <span class="inline-block bg-green-100 text-green-800 px-3 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-check-circle mr-1"></i>Sudah Bayar
                    </span>
                @elseif($order->payment_status === 'rejected')
                    <span class="inline-block bg-red-100 text-red-800 px-3 py-2 rounded-lg font-semibold text-sm">
                        <i class="fa-solid fa-times-circle mr-1"></i>Ditolak
                    </span>
                @endif
            </div>

            <!-- Metode Pembayaran -->
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-2">METODE PEMBAYARAN</p>
                <p class="font-semibold text-gray-800">
                    @if($order->payment_method === 'transfer')
                        <i class="fa-solid fa-bank text-purple-600 mr-1"></i>Transfer Bank
                    @else
                        {{ ucfirst($order->payment_method ?? 'Belum ditentukan') }}
                    @endif
                </p>
            </div>

            <!-- Ongkos Kirim -->
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-2">ONGKOS KIRIM</p>
                <p class="font-semibold text-green-600">
                    <i class="fa-solid fa-check-circle mr-1"></i>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $order->shipping ? $order->shipping->name : 'Standar' }}
                </p>
            </div>

            <!-- Total -->
            <div>
                <p class="text-gray-600 text-sm font-semibold mb-2">TOTAL PEMBAYARAN</p>
                <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fa-solid fa-box text-purple-600 mr-2"></i>Detail Produk
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Produk</th>
                        <th class="px-6 py-3 text-center text-gray-700 font-bold">Harga</th>
                        <th class="px-6 py-3 text-center text-gray-700 font-bold">Jumlah</th>
                        <th class="px-6 py-3 text-right text-gray-700 font-bold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded"></div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-600">SKU: {{ $item->product->sku ?? '-' }}</p>
                                </div>
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Biaya -->
        <div class="px-6 py-6 bg-gray-50 border-t flex justify-end">
            <div class="w-full md:w-1/2 lg:w-1/3">
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Ongkos Kirim:</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between font-bold text-lg text-purple-600">
                        <span>Total:</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pembayaran Transfer Bank (Jika Pending) -->
    @if($order->payment_status === 'pending' && $order->payment_method === 'transfer')
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-l-orange-600">
        <div class="px-6 py-4 bg-orange-50 border-b">
            <h3 class="text-lg font-bold text-orange-900 flex items-center">
                <i class="fa-solid fa-university text-orange-600 mr-2"></i>Instruksi Pembayaran via Transfer Bank
            </h3>
        </div>
        
        <div class="px-6 py-6">
            <div class="mb-6 p-4 bg-orange-100 rounded-lg border border-orange-300">
                <p class="text-orange-900 font-semibold flex items-center">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    Silakan transfer sesuai jumlah total di bawah ke salah satu rekening tujuan
                </p>
            </div>

            <!-- Jumlah Transfer -->
            <div class="mb-6 p-4 bg-purple-50 rounded-lg border-2 border-purple-300">
                <p class="text-gray-600 text-sm mb-2">Jumlah yang Harus Ditransfer:</p>
                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p class="text-xs text-orange-600 mt-2">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Mohon transfer sesuai jumlah yang tertulis untuk memudahkan verifikasi
                </p>
            </div>

            <!-- Rekening Tujuan -->
            <div class="space-y-4">
                <p class="font-bold text-gray-800 mb-4">
                    <i class="fa-solid fa-list mr-2"></i>Rekening Tujuan:
                </p>

                @php
                    $bankAccounts = config('banking.accounts');
                @endphp

                @forelse($bankAccounts as $account)
                <div class="p-4 border-2 border-gray-200 rounded-lg hover:border-purple-400 hover:bg-purple-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 mb-1">
                                <i class="fa-solid fa-circle text-{{ $account['icon_color'] }}-600 text-xs mr-2"></i>
                                {{ $account['bank'] }}
                            </p>
                            <p class="text-sm text-gray-700 mb-1">
                                <strong>No. Rekening:</strong> <span class="font-mono text-lg">{{ $account['account_number'] }}</span>
                            </p>
                            <p class="text-sm text-gray-700">
                                <strong>Atas Nama:</strong> {{ $account['account_holder'] ?? config('app.name') }}
                            </p>
                        </div>
                        <button onclick="copyToClipboard('{{ $account['account_number'] }}')" 
                                class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm font-semibold transition">
                            <i class="fa-solid fa-copy mr-1"></i>Salin
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700">Rekening tujuan belum dikonfigurasi. Hubungi administrator.</p>
                </div>
                @endforelse
            </div>

            <!-- Instruksi & Tips -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="font-bold text-blue-900 mb-3 flex items-center">
                    <i class="fa-solid fa-lightbulb text-blue-600 mr-2"></i>Tips:
                </p>
                <ul class="text-sm text-blue-900 space-y-2">
                    <li><i class="fa-solid fa-check text-blue-600 mr-2"></i>Gunakan <strong>nama Anda atau nama bisnis</strong> sebagai keterangan transfer</li>
                    <li><i class="fa-solid fa-check text-blue-600 mr-2"></i>Transfer dari rekening apapun (tidak harus rekening dengan nama yang sama)</li>
                    <li><i class="fa-solid fa-check text-blue-600 mr-2"></i>Admin akan mengkonfirmasi pembayaran dalam <strong>maksimal 1x24 jam</strong></li>
                </ul>
            </div>

            <!-- Upload Bukti (Optional - for future) -->
            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm text-gray-600">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Setelah transfer, admin akan memverifikasi pembayaran Anda. Anda akan menerima notifikasi via email ketika pembayaran sudah dikonfirmasi.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Pengiriman -->
    @if($order->status === 'shipped' || $order->tracking_number)
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
            <h3 class="text-lg font-bold text-green-800 flex items-center">
                <i class="fa-solid fa-truck text-green-600 mr-2"></i>Informasi Pengiriman
            </h3>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                    <span class="text-gray-700 font-semibold">Nomor Resi:</span>
                    <span class="text-green-600 font-bold text-lg">{{ $order->tracking_number }}</span>
                </div>
                <p class="text-sm text-gray-600">Pesanan Anda sedang dalam perjalanan. Anda dapat melacak paket menggunakan nomor resi di atas.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Tombol Aksi -->
    <div class="mt-6 flex gap-3 flex-wrap">
        <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
            <i class="fa-solid fa-arrow-left"></i>Kembali
        </a>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
            <i class="fa-solid fa-shopping-bag"></i>Lanjut Belanja
        </a>
        
        @if($order->status === 'shipped')
        <form action="{{ route('customer.orders.confirmDelivery', $order->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition"
                    onclick="return confirm('Konfirmasi bahwa pesanan sudah Anda terima?')">
                <i class="fa-solid fa-check-double"></i>Konfirmasi Sudah Terima
            </button>
        </form>
        @endif
    </div>
</div>

<script>
function copyToClipboard(text) {
    // Create a temporary textarea element
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    
    // Select and copy
    textarea.select();
    document.execCommand('copy');
    
    // Remove the temporary element
    document.body.removeChild(textarea);
    
    // Show feedback to user
    alert('Nomor rekening berhasil disalin ke clipboard!');
}
</script>

@endsection
