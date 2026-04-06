@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

@if(session('success'))
    <div style="padding:10px; background-color:#DCEDC8; color:#558B2F; margin-bottom:15px; border-radius:8px;">
        {{ session('success') }}
    </div>
@endif

<div style="display:flex; justify-content:flex-end; margin-bottom:15px;">
    <a href="{{ route('users.create') }}" 
       style="background-color:#8BC34A; color:white; font-weight:bold; border-radius:8px; padding:8px 16px; text-decoration:none; transition:0.2s;">
       <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

<table style="width:100%; border-collapse: collapse;">
    <thead style="background-color:#F1F8E9; color:#558B2F; font-weight:bold;">
        <tr>
            <th style="padding:12px; border:1px solid #ddd;">No</th>
            <th style="padding:12px; border:1px solid #ddd;">Nama</th>
            <th style="padding:12px; border:1px solid #ddd;">Email</th>
            <th style="padding:12px; border:1px solid #ddd;">Role</th>
            <th style="padding:12px; border:1px solid #ddd;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
        <tr style="background-color:white; border-bottom:1px solid #ddd; text-align:center; transition:0.2s;">
            <td style="padding:10px;">{{ $index + 1 }}</td>
            <td style="padding:10px; color:#558B2F; font-weight:bold;">{{ $user->name }}</td>
            <td style="padding:10px;">{{ $user->email }}</td>
            <td style="padding:10px;">{{ ucfirst($user->role) }}</td>
            <td style="padding:10px;">
                
                <a href="{{ route('users.edit', $user->id) }}" 
                   style="background-color:#8BC34A; color:white; border:none; border-radius:5px; padding:6px 10px; margin-right:5px; text-decoration:none; display:inline-block; transition:0.3s;">
                    <i class="fas fa-pen"></i>
                </a>

                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            style="background-color:#ff7f7f; color:white; border:none; border-radius:5px; padding:6px 10px; cursor:pointer; transition:0.3s;">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="padding:15px; border:1px solid #ddd; text-align:center; color:#558B2F;">
                Tidak ada user
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection