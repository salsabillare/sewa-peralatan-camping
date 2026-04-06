@extends('layouts.app')

@section('title', 'Tambah User')

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

<form action="{{ route('users.store') }}" method="POST" style="max-width:500px;">
    @csrf

    <label>Nama</label><br>
    <input type="text" name="name" value="{{ old('name') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email') }}" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Password</label><br>
    <input type="password" name="password" required class="card" style="width:100%; margin-bottom:10px;"><br>

    <label>Role</label><br>
<select name="role" required class="card" style="width:100%; margin-bottom:10px;">
    <option value="">-- Pilih Role --</option>
    <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
    <option value="kasir" {{ old('role')=='kasir' ? 'selected' : '' }}>Kasir</option>
    <option value="customer" {{ old('role')=='customer' ? 'selected' : '' }}>Customer</option>
</select><br>


    <button type="submit" class="btn-edit"><i class="fas fa-plus"></i> Tambah User</button>
</form>
@endsection
