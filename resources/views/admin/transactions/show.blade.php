@extends('layouts.app')

@section('title', 'Detail Transaksi Kasir')

@section('content')

<div class="container mx-auto px-4 py-8">
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.transactions.index') }}" class="text-purple-600 hover:text-purple-800">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar Transaksi
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Detail Transaksi #{{ $transaction->transaction_code }}</h1>

    <!-- Info Kasir dan Tanggal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Kasir Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-green-700 mb-4">
                <i class="fa-solid fa-user mr-2"></i>Informasi Kasir
            </h3>
            <div class="space-y-2">
                <p><strong>Nama Kasir:</strong> {{ $transaction->user->name }}</p>
                <p><strong>Email:</strong> {{ $transaction->user->email }}</p>
                <p><strong>Kode Transaksi:</strong> <span class="font-mono text-green-700 font-bold">{{ $transaction->transaction_code }}</span></p>
                <p><strong>Tanggal Transaksi:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Status Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-green-700 mb-4">
                <i class="fa-solid fa-info-circle mr-2"></i>Informasi Transaksi
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Status:</p>
                    @if ($transaction->status == 'paid')
                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-check-circle mr-1"></i>Lunas
                        </span>
                    @elseif ($transaction->status == 'pending')
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-hourglass mr-1"></i>Menunggu
                        </span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fa-solid fa-times-circle mr-1"></i>Batal
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Item:</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $transaction->items->count() }} item</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-green-100 border-b">
            <h3 class="text-lg font-bold text-green-800">
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
                    @forelse($transaction->items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->product->name ?? '-' }}</p>
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
                            Belum ada item dalam transaksi ini
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
                    <p class="text-lg"><strong>Total Transaksi:</strong></p>
                    <p class="text-3xl font-bold text-green-700">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pembayaran -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-green-600 mb-4">
            <i class="fa-solid fa-wallet mr-2"></i>Informasi Pembayaran
        </h3>
        
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-700 font-semibold">Total Tagihan:</span>
                <span class="font-semibold text-lg text-green-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-700 font-semibold">Pembayaran:</span>
                <span class="font-semibold text-lg text-green-600">Rp {{ number_format($transaction->payment, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="font-bold text-lg">Kembalian:</span>
                <span class="font-bold text-lg text-green-600">Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
            </div>
            <p class="text-sm text-green-700 mt-3">
                <i class="fa-solid fa-info-circle mr-1"></i>
                Transaksi kasir (offline) - pembayaran langsung lunas.
            </p>
        </div>
    </div>

    <!-- Status Pengembalian -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 mb-6">
        <h3 class="text-lg font-bold text-green-600 mb-4">
            <i class="fa-solid fa-undo mr-2"></i>Status Pengembalian Barang
        </h3>
        
        @php
            $totalItems = $transaction->items->count();
            $returnedItems = $transaction->items->whereNotNull('returned_at')->count();
            $notReturnedItems = $totalItems - $returnedItems;
        @endphp

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-700 font-semibold">Total Barang:</span>
                <span class="text-lg font-bold text-gray-800">{{ $totalItems }} item</span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-700 font-semibold">Sudah Dikembalikan:</span>
                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                    {{ $returnedItems }} item
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-700 font-semibold">Belum Dikembalikan:</span>
                <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-semibold">
                    {{ $notReturnedItems }} item
                </span>
            </div>

            @if($notReturnedItems > 0)
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="font-semibold text-yellow-800 mb-2">Barang yang belum dikembalikan:</p>
                    <ul class="space-y-2">
                        @foreach($transaction->items->whereNull('returned_at') as $item)
                            <li class="text-gray-700">
                                <i class="fa-solid fa-circle text-xs mr-2"></i>
                                <span class="font-semibold">{{ $item->product->name ?? '-' }}</span> 
                                <span class="text-gray-600">({{ $item->quantity }}x)</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                    <p class="text-green-700 font-semibold">
                        <i class="fa-solid fa-check-circle mr-2"></i>Semua barang telah dikembalikan
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Update Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 mb-6">
        <h3 class="text-lg font-bold text-green-600 mb-4">
            <i class="fa-solid fa-edit mr-2"></i>Update Status Transaksi
        </h3>
        
        <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status Saat Ini:</label>
                <div class="inline-block">
                    @if($transaction->status === 'paid')
                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-check-circle mr-1"></i>Lunas
                        </span>
                    @elseif($transaction->status === 'pending')
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-hourglass-half mr-1"></i>Menunggu
                        </span>
                    @elseif($transaction->status === 'cancelled')
                        <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                            <i class="fa-solid fa-times-circle mr-1"></i>Dibatalkan
                        </span>
                    @else
                        <span class="inline-block bg-gray-100 text-gray-800 px-4 py-2 rounded-lg font-semibold">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <label for="status" class="block text-gray-700 font-semibold mb-2">Ubah Status:</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600">
                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="paid" {{ $transaction->status === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-gray rounded-lg font-semibold transition">
                    <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.transactions.index') }}" 
           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
    </div>

</div>

@endsection
