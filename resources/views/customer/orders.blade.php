@extends('layouts.app')

@section('title', 'Riwayat Order')

@section('content')
<h2 style="color:#4b0082; margin-bottom:20px;">Riwayat Order Anda</h2>

@if(session('success'))
<div style="padding:10px; background-color:#e0d4ff; color:#4b0082; margin-bottom:15px; border-radius:8px;">
    {{ session('success') }}
</div>
@endif

@if($orders->isEmpty())
<p>Belum ada order.</p>
@else
<table style="width:100%; border-collapse: collapse;">
    <thead style="background-color:#e9e0ff; color:#4b0082; font-weight:bold;">
        <tr>
            <th style="padding:10px;">Kode</th>
            <th style="padding:10px;">Total</th>
            <th style="padding:10px;">Metode</th>
            <th style="padding:10px;">Status</th>
            <th style="padding:10px;">Tanggal</th>
            <th style="padding:10px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr style="text-align:center;">
            <td style="padding:10px;">{{ $order->id }}</td>
            <td style="padding:10px;">Rp {{ number_format($order->total_price,0,',','.') }}</td>
            <td style="padding:10px;">{{ $order->payment_method }}</td>
            <td style="padding:10px;">
                <span style="padding:5px 12px; background:#e0d4ff; color:#4b0082; border-radius:6px;">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td style="padding:10px;">{{ $order->created_at->format('d-m-Y H:i') }}</td>
            <td style="padding:10px;">
                <a href="{{ route('shop.orders.show', $order->id) }}" 
                    style="background:#b090ff; color:white; padding:8px 12px; border-radius:8px; text-decoration:none;">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
