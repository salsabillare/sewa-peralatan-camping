@extends('layouts.customer')

@section('title', 'Edit Profil - CampGear Hub')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Edit Profil</h1>

    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-200 text-red-900">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="bg-purple-50 p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block font-semibold text-purple-800 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold text-purple-800 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold text-purple-800 mb-1">Alamat</label>
            <textarea name="address" class="w-full border rounded p-2">{{ old('address', $user->address) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-purple-800 mb-1">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
