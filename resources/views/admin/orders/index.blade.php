@extends('layouts.app')

@section('title', 'Order Online')

@section('content')

<div class="container mx-auto px-4 py-8">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg">
            {{ session('warning') }}
        </div>
    @endif

    @php
        $pendingPayments = $orders->where('payment_status', 'pending')->count();
        $confirmedPayments = $orders->where('payment_status', 'confirmed')->count();
    @endphp

    @if($pendingPayments > 0)
    <div class="mb-6 p-4 bg-orange-50 border-l-4 border-l-orange-600 rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-orange-900 text-lg">⏳ Pembayaran Menunggu Konfirmasi</p>
                <p class="text-orange-800">{{ $pendingPayments }} pesanan membutuhkan verifikasi pembayaran dari Anda</p>
            </div>
            <a href="#pending-payments" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded font-semibold transition">
                <i class="fa-solid fa-arrow-down mr-1"></i>Lihat Sekarang
            </a>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:#F1F8E9;">
                    <tr style="color:#558B2F;">
                        <th class="px-2 py-2 text-left font-bold">#</th>
                        <th class="px-2 py-2 text-left font-bold">Customer</th>
                        <th class="px-2 py-2 text-left font-bold">Produk</th>
                        <th class="px-2 py-2 text-center font-bold">Total</th>
                        <th class="px-2 py-2 text-center font-bold">Status</th>
                        <th class="px-2 py-2 text-center font-bold">Bayar</th>
                        <th class="px-2 py-2 text-center font-bold">Pengembalian</th>
                        <th class="px-2 py-2 text-center font-bold">Tanggal</th>
                        <th class="px-2 py-2 text-center font-bold">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-2 py-2">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>

                            <td class="px-2 py-2">
                                <p class="font-semibold text-gray-800 text-xs">{{ substr($order->user->name ?? '-', 0, 15) }}</p>
                            </td>

                            <td class="px-2 py-2">
                                <div class="space-y-0.5">
                                    @forelse($order->items as $item)
                                        <p class="text-xs">
                                            <span class="font-semibold">{{ substr($item->product->name ?? '-', 0, 12) }}</span>
                                            <span class="text-gray-600">({{ $item->quantity }})</span>
                                        </p>
                                    @empty
                                        <p class="text-gray-500 text-xs">-</p>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-2 py-2 text-right">
                                <p class="font-bold text-green-700 text-xs">
                                    Rp {{ number_format($order->total_price / 1000, 0) }}K
                                </p>
                            </td>

                            <td class="px-2 py-2 text-center">
                                <div class="flex flex-col gap-1 items-center">
                                    @if($order->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Menunggu
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Diproses
                                        </span>
                                    @elseif($order->status === 'shipped')
                                        <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Dikirim
                                        </span>
                                    @elseif($order->status === 'delivered')
                                        <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Terima
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif

                                    @if(!$order->shipping_cost_confirmed)
                                        <span class="bg-red-100 text-red-800 px-1 py-0.5 rounded text-xs font-semibold">
                                            Ongkir Pending
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-2 py-2 text-center">
                                @if($order->payment_status === 'pending')
                                    <span class="bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-xs font-semibold">
                                        Menunggu
                                    </span>
                                @elseif($order->payment_status === 'confirmed')
                                    <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">
                                        Terbayar
                                    </span>
                                @elseif($order->payment_status === 'rejected')
                                    <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td class="px-2 py-2 text-center">
                                @php
                                    $totalItems = $order->items->count();
                                    $returnedItems = $order->items->whereNotNull('returned_at')->count();
                                @endphp

                                @if($totalItems > 0)
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">
                                        {{ $returnedItems }}/{{ $totalItems }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">-</span>
                                @endif
                            </td>

                            <td class="px-2 py-2 text-center text-gray-600 text-xs">
                                {{ $order->created_at->format('d M') }}
                            </td>

                            <td class="px-2 py-2 text-center">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   style="background:#8BC34A;color:white;padding:5px 10px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">
                                   <i class="fa-solid fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-2 py-8 text-center text-gray-500" colspan="9">
                                <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="mt-2">Belum ada pemesanan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif

</div>

@endsection