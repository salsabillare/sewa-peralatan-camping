@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

@if ($errors->any())
    <div style="background-color:#ffcccc; color:#800000; padding:10px; margin-bottom:10px; border-radius:5px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" style="max-width:500px;">
    @csrf

    <label>Nama</label><br>
    <input type="text" name="name" value="{{ old('name') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>SKU</label><br>
    <input type="text" name="sku" value="{{ old('sku') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Harga</label><br>
    <input type="number" name="price" value="{{ old('price') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Stok</label><br>
    <input type="number" name="stock" value="{{ old('stock') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Kategori</label><br>
    <select name="category_id" class="card" style="width:100%; margin-bottom:10px;">
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select><br>

    <label>Gambar</label><br>
    <input type="file" name="image" class="card" style="width:100%; margin-bottom:10px;"><br>

    <button type="submit" 
            style="background-color:#c9a6ff; color:white; font-weight:bold; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; transition:0.2s;">
        <i class="fas fa-plus"></i> Tambah Produk
    </button>
</form>
@endsection
