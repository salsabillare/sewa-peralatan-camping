@extends('layouts.app')

@section('title', 'Transaksi Kasir')

@section('content')

<div class="container mx-auto px-4 py-8">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background:#F1F8E9;">
                    <tr style="color:#558B2F;">
                        <th class="px-6 py-3 text-left font-bold">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left font-bold">Kasir</th>
                        <th class="px-6 py-3 text-left font-bold">Produk</th>
                        <th class="px-6 py-3 text-center font-bold">Total</th>
                        <th class="px-6 py-3 text-center font-bold">Status Pengembalian</th>
                        <th class="px-6 py-3 text-center font-bold">Tanggal</th>
                        <th class="px-6 py-3 text-center font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-6 py-4">
                                <span style="color:#558B2F;font-weight:600;">
                                    {{ $transaction->transaction_code }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">
                                    {{ $transaction->user->name ?? '-' }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @forelse($transaction->items as $item)
                                        <p class="text-sm">
                                            <span class="font-semibold">
                                                {{ $item->product->name ?? '-' }}
                                            </span>
                                            <span class="text-gray-600">
                                                ({{ $item->quantity }}x)
                                            </span>
                                        </p>
                                    @empty
                                        <p class="text-gray-500">-</p>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <p style="color:#558B2F;font-weight:bold;">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @php
                                    $totalItems = $transaction->items->count();
                                    $returnedItems = $transaction->items->whereNotNull('returned_at')->count();
                                    $notReturnedItems = $totalItems - $returnedItems;
                                @endphp
                                
                                @if($totalItems > 0)

                                    <div class="space-y-2">

                                        @if($returnedItems > 0)
                                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold inline-block">
                                                <i class="fa-solid fa-check-circle mr-1"></i>
                                                {{ $returnedItems }}/{{ $totalItems }} Kembali
                                            </div>
                                        @endif

                                        @if($notReturnedItems > 0)
                                            <div>
                                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                    <i class="fa-solid fa-hourglass-half mr-1"></i>
                                                    {{ $notReturnedItems }} Belum
                                                </span>
                                            </div>
                                        @endif

                                        @if($returnedItems === $totalItems && $totalItems > 0)
                                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold inline-block">
                                                <i class="fa-solid fa-check-circle mr-1"></i>
                                                Semua Kembali
                                            </div>
                                        @endif

                                    </div>

                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ $transaction->created_at->format('d M Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                   style="background:#8BC34A;color:white;padding:8px 12px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">
                                   <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td class="px-6 py-8 text-center text-gray-500" colspan="7">
                                <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="mt-2">Belum ada transaksi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection