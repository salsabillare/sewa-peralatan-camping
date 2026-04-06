{{-- resources/views/kasir/products/index.blade.php --}}
@extends('layouts.kasir')

@section('title', 'Daftar Produk')

@section('content')
<div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #e9e0ff; color: #4b0082;">
                <tr>
                    <th style="padding: 10px;">Gambar</th>
                    <th style="padding: 10px;">Nama Produk</th>
                    <th style="padding: 10px;">SKU</th>
                    <th style="padding: 10px;">Harga</th>
                    <th style="padding: 10px;">Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px; text-align:center;">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 width="60" 
                                 style="border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        @else
                            <span style="color:#aaa;">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td style="padding: 10px;">{{ $product->name }}</td>
                    <td style="padding: 10px;">{{ $product->sku }}</td>
                    <td style="padding: 10px;">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td style="padding: 10px;">{{ $product->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
