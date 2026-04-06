@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

@if ($errors->any())
    <div style="background-color:#ffcccc; color:#800000; padding:10px; margin-bottom:15px; border-radius:8px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" style="max-width:500px;">
    @csrf
    @method('PUT')

    <label style="font-weight:bold; color:#4b0082;">Nama</label><br>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="card" style="width:100%; margin-bottom:10px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;"><br>

    <label style="font-weight:bold; color:#4b0082;">SKU</label><br>
    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="card" style="width:100%; margin-bottom:10px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;"><br>

    <label style="font-weight:bold; color:#4b0082;">Harga</label><br>
    <input type="number" name="price" value="{{ old('price', $product->price) }}" required class="card" style="width:100%; margin-bottom:10px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;"><br>

    <label style="font-weight:bold; color:#4b0082;">Stok</label><br>
    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="card" style="width:100%; margin-bottom:10px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;"><br>

    <label style="font-weight:bold; color:#4b0082;">Kategori</label><br>
    <select name="category_id" class="card" style="width:100%; margin-bottom:15px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;">
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select><br>

    <label style="font-weight:bold; color:#4b0082;">Gambar</label><br>
    <input type="file" name="image" class="card" style="width:100%; margin-bottom:20px; padding:8px; border-radius:8px; border:1px solid #c9a6ff;"><br>

    <button type="submit" 
            style="background-color:#c9a6ff; color:white; font-weight:bold; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; transition:0.2s;">
        <i class="fas fa-pen"></i> Update Produk
    </button>
</form>

@endsection
