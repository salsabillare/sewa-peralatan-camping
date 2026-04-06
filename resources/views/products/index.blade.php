@extends('layouts.app')

@section('title', 'Daftar Peralatan Camping')

@section('content')

@if(session('success'))
    <div style="padding:10px; background-color:#DCEDC8; color:#558B2F; margin-bottom:15px; border-radius:8px;">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('products.create') }}" class="btn-edit" style="margin-bottom:20px; display:inline-block; background-color:#8BC34A; color:white; font-weight:bold; padding:8px 16px; border-radius:8px; text-decoration:none; transition:0.2s;">
    <i class="fas fa-plus"></i> Tambah Peralatan
</a>

@if ($products->isEmpty())
    <p style="color:#558B2F;">Tidak ada peralatan.</p>
@else
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px;">
        @foreach ($products as $product)
            <div class="card" style="background-color:#F1F8E9; border-radius:12px; padding:15px; box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:0.2s; border-left: 4px solid #8BC34A;">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:100%; height:150px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                @else
                    <div style="width:100%; height:150px; background-color:#AED581; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; margin-bottom:10px;">
                        Tidak Ada Gambar
                    </div>
                @endif

                <h3 style="margin-bottom:5px; color:#558B2F; font-weight:bold;">{{ $product->name }}</h3>
                <p style="margin:2px 0; color:#558B2F;">Harga: Rp {{ number_format($product->price,0,',','.') }}/hari</p>
                <p style="margin:2px 0; color:#558B2F;">Stok: {{ $product->stock }}</p>
                <p style="margin:2px 0; color:#558B2F;">Kategori: {{ $product->category ? $product->category->name : '-' }}</p>

                <div style="margin-top:10px; display:flex; justify-content:center; gap:10px;">
                    <a href="{{ route('products.edit', $product->id) }}" style="background-color:#8BC34A; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; transition:0.2s;">
                        <i class="fas fa-pen"></i>
                    </a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peralatan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color:#ff7f7f; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; transition:0.2s;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
