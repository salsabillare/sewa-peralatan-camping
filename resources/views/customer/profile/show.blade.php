@extends('layouts.customer')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-400 to-purple-600 px-6 py-8">
            <h2 class="text-3xl font-bold text-white">Profil Saya</h2>
            <p class="text-purple-100 mt-1">Kelola informasi akun Anda</p>
        </div>

        <!-- Konten -->
        <div class="p-6">
            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Nama -->
                <div class="border-l-4 border-purple-600 pl-4">
                    <p class="text-gray-600 text-sm font-semibold mb-1">NAMA</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                </div>

                <!-- Email -->
                <div class="border-l-4 border-purple-600 pl-4">
                    <p class="text-gray-600 text-sm font-semibold mb-1">EMAIL</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                </div>

                <!-- Telepon -->
                <div class="border-l-4 border-purple-600 pl-4">
                    <p class="text-gray-600 text-sm font-semibold mb-1">TELEPON</p>
                    <p class="text-lg font-semibold text-gray-800">
                        @if($user->phone)
                            {{ $user->phone }}
                        @else
                            <span class="text-gray-400">Belum diisi</span>
                        @endif
                    </p>
                </div>

                <!-- Role -->
                <div class="border-l-4 border-purple-600 pl-4">
                    <p class="text-gray-600 text-sm font-semibold mb-1">TIPE AKUN</p>
                    <p class="text-lg font-semibold text-gray-800 capitalize">
                        @if($user->role === 'customer')
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Pelanggan</span>
                        @elseif($user->role === 'admin')
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Admin</span>
                        @else
                            <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ ucfirst($user->role) }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Alamat -->
            <div class="mb-8 border-l-4 border-purple-600 pl-4">
                <p class="text-gray-600 text-sm font-semibold mb-2">ALAMAT LENGKAP</p>
                <p class="text-gray-800">
                    @if($user->address)
                        {{ $user->address }}
                    @else
                        <span class="text-gray-400 italic">Belum diisi</span>
                    @endif
                </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-3">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
                    <i class="fa-solid fa-edit"></i>
                    Edit Profil
                </a>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Belanja
                </a>
            </div>
        </div>
    </div>

    <!-- Info Tambahan -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">
            <i class="fa-solid fa-info-circle mr-2"></i>
            Informasi Penting
        </h3>
        <ul class="text-blue-800 text-sm space-y-2">
            <li>✓ Pastikan alamat lengkap untuk memastikan pengiriman berjalan lancar</li>
            <li>✓ Gunakan nomor telepon aktif yang dapat dihubungi</li>
            <li>✓ Email digunakan untuk notifikasi pesanan dan keamanan akun</li>
        </ul>
    </div>
</div>
@endsection
