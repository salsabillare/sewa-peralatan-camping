@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<h2 style="color:#4b0082; margin-bottom:20px;">Keranjang Anda</h2>

@if(session('success'))
<div style="padding:10px; background-color:#e0d4ff; color:#4b0082; margin-bottom:15px; border-radius:8px;">
    {{ session('success') }}
</div>
@endif

@if($cartItems->isEmpty())
<p>Keranjang kosong.</p>
@else
<table style="width:100%; border-collapse: collapse;">
    <thead style="background-color:#e9e0ff; color:#4b0082; font-weight:bold;">
        <tr>
            <th style="padding:10px;">Produk</th>
            <th style="padding:10px;">Harga</th>
            <th style="padding:10px;">Qty</th>
            <th style="padding:10px;">Subtotal</th>
            <th style="padding:10px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cartItems as $item)
        <tr style="text-align:center;">
            <td style="padding:10px;">{{ $item->product->name }}</td>
            <td style="padding:10px;">Rp {{ number_format($item->product->price,0,',','.') }}</td>
            <td style="padding:10px;">{{ $item->quantity }}</td>
            <td style="padding:10px;">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
            <td style="padding:10px;">
                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button style="background:#b090ff; color:white; padding:5px 10px; border-radius:6px; border:none;">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align:right; padding:10px;"><strong>Total:</strong></td>
            <td colspan="2" style="text-align:center; padding:10px;">
                <strong>Rp {{ number_format($cartTotal,0,',','.') }}</strong>
            </td>
        </tr>
    </tbody>
</table>

<a href="{{ route('checkout.index') }}" style="margin-top:15px; display:inline-block; padding:10px 15px; background:#7c4dff; color:white; border-radius:8px;">Checkout</a>
@endif

@endsection
