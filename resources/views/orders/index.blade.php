@extends('layouts.app')

@section('title', 'Orders')

@section('content')

<div class="card-body" style="padding:20px;">
    <table style="width:100%; border-collapse: collapse;">
        <thead style="background-color:#e9e0ff; color:#4b0082; font-weight:bold;">
            <tr>
                <th style="padding:12px; text-align:left;">#</th>
                <th style="padding:12px; text-align:left;">Pelanggan</th>
                <th style="padding:12px; text-align:left;">Total</th>
                <th style="padding:12px; text-align:left;">Status</th>
                <th style="padding:12px; text-align:left;">Tanggal</th>
                <th style="padding:12px; text-align:left;">Alamat</th>
                <th style="padding:12px; text-align:left;">Metode Bayar</th>
                <th style="padding:12px; text-align:left;">Catatan</th>
                <th style="padding:12px; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr style="border-bottom:1px solid #ddd; background-color:white; transition:0.2s;">
                <td style="padding:10px;">{{ $loop->iteration }}</td>
                <td style="padding:10px;">{{ $order->user->name }}</td>
                <td style="padding:10px;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td style="padding:10px;">
                    @if($order->status == 'pending')
                        <span style="background-color:#fff3cd; color:#856404; padding:3px 10px; border-radius:20px; font-size:13px;">Menunggu</span>
                    @elseif($order->status == 'success')
                        <span style="background-color:#d9fdd3; color:#2e7d32; padding:3px 10px; border-radius:20px; font-size:13px;">Selesai</span>
                    @else
                        <span style="background-color:#f8d7da; color:#842029; padding:3px 10px; border-radius:20px; font-size:13px;">{{ ucfirst($order->status) }}</span>
                    @endif
                </td>
                <td style="padding:10px;">{{ $order->created_at->format('d M Y') }}</td>
                <td style="padding:10px;">{{ $order->address ?? '-' }}</td>
                <td style="padding:10px;">{{ ucfirst($order->payment_method ?? '-') }}</td>
                <td style="padding:10px;">{{ $order->note ?? '-' }}</td>
                <td style="padding:10px; text-align:center;">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       style="background-color:#c9a6ff; color:white; padding:6px 12px; border-radius:6px; font-size:13px; text-decoration:none; margin-right:5px; display:inline-block; transition:0.2s;">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding:15px; text-align:center; color:#888;">
                    Belum ada order yang tercatat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
