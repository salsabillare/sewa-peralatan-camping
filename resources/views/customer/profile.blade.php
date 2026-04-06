@extends('layouts.customer')

@section('title', 'Profil Saya - CampGear Hub')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-purple-700 mb-6">Profil Saya</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-purple-200 text-purple-900">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-purple-50 p-6 rounded-lg shadow space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-purple-800">Informasi Pribadi</h2>
            <a href="{{ route('profile.edit') }}" class="text-white bg-purple-600 px-4 py-2 rounded hover:bg-purple-700 transition">
                Edit
            </a>
        </div>

        <div class="space-y-2">
            <p><span class="font-semibold text-purple-800">Nama:</span> {{ $user->name }}</p>
            <p><span class="font-semibold text-purple-800">Email:</span> {{ $user->email }}</p>
            <p><span class="font-semibold text-purple-800">Alamat:</span> {{ $user->address ?? '-' }}</p>
            <p><span class="font-semibold text-purple-800">No. Telepon:</span> {{ $user->phone ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
