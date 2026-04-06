@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')

@if(session('success'))
    <div style="padding:10px; background-color:#DCEDC8; color:#558B2F; margin-bottom:15px; border-radius:8px;">
        {{ session('success') }}
    </div>
@endif

<div style="display:flex; justify-content:flex-end; margin-bottom:15px;">
    <a href="{{ route('categories.create') }}" 
       style="background-color:#8BC34A; color:white; font-weight:bold; border-radius:8px; padding:8px 16px; text-decoration:none; box-shadow:0 2px 5px rgba(0,0,0,0.1); transition:0.2s;">
       <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<div style="overflow-x:auto;">
<table style="width:100%; border-collapse: collapse; min-width:600px;">
    <thead style="background-color:#F1F8E9; color:#558B2F; font-weight:bold;">
        <tr>
            <th style="padding:12px; border-bottom:2px solid #8BC34A; text-align:left;">ID</th>
            <th style="padding:12px; border-bottom:2px solid #8BC34A; text-align:left;">Nama Kategori</th>
            <th style="padding:12px; border-bottom:2px solid #8BC34A; text-align:left;">Deskripsi</th>
            <th style="padding:12px; border-bottom:2px solid #8BC34A; text-align:center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr style="background-color:white; transition:0.2s;">
            <td style="padding:10px; border-bottom:1px solid #ddd;">{{ $category->id }}</td>
            <td style="padding:10px; border-bottom:1px solid #ddd; color:#558B2F; font-weight:bold;">{{ $category->name }}</td>
            <td style="padding:10px; border-bottom:1px solid #ddd;">{{ $category->description ?? '-' }}</td>
            <td style="padding:10px; border-bottom:1px solid #ddd; text-align:center;">
                
                <a href="{{ route('categories.edit', $category->id) }}" 
                   style="margin-right:5px; background-color:#8BC34A; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; transition:0.2s;">
                    <i class="fas fa-pen"></i>
                </a>

                <form action="{{ route('categories.destroy', $category->id) }}" 
                      method="POST" 
                      style="display:inline;" 
                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            style="background-color:#ff7f7f; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; transition:0.2s;">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="padding:15px; text-align:center; color:#558B2F;">
                Tidak ada kategori
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

@endsection