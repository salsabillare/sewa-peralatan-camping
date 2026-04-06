@extends('layouts.kasir')

@section('title', 'Detail Transaksi')

@section('content')
<div class="card" style="background-color:#f7f0ff; border-radius:12px; padding:20px; max-width:600px; margin:auto;">
    <h2 style="color:#4b0082; font-weight:bold; margin-bottom:15px;">Transaksi #{{ $transaction->id }}</h2>

    <h3 style="color:#4b0082; font-weight:bold; margin-bottom:10px;">Daftar Produk:</h3>
    @if($transaction->items->count() > 0)
        <table style="width:100%; border-collapse: collapse; margin-bottom:15px;">
            <thead style="background-color:#e0d4ff; color:#4b0082;">
                <tr>
                    <th style="padding:8px; border:1px solid #c9a6ff;">Produk</th>
                    <th style="padding:8px; border:1px solid #c9a6ff;">Harga</th>
                    <th style="padding:8px; border:1px solid #c9a6ff;">Jumlah</th>
                    <th style="padding:8px; border:1px solid #c9a6ff;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                    <tr>
                        <td style="padding:8px; border:1px solid #c9a6ff;">{{ $item->product->name ?? '-' }}</td>
                        <td style="padding:8px; border:1px solid #c9a6ff;">Rp{{ number_format($item->price,0,',','.') }}</td>
                        <td style="padding:8px; border:1px solid #c9a6ff;">{{ $item->quantity }}</td>
                        <td style="padding:8px; border:1px solid #c9a6ff;">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Produk: {{ $transaction->product->name ?? '-' }} x {{ $transaction->quantity ?? '-' }}</p>
    @endif

    <p><strong>Total:</strong> Rp{{ number_format($transaction->total,0,',','.') }}</p>
    <p><strong>Bayar:</strong> Rp{{ number_format($transaction->payment,0,',','.') }}</p>
    <p><strong>Kembalian:</strong> Rp{{ number_format($transaction->change,0,',','.') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
    <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>

    <a href="{{ route('kasir.transactions.index') }}" 
       style="background-color:#c9a6ff; color:white; padding:8px 16px; border-radius:8px; text-decoration:none;">Kembali</a>
</div>
@endsection
