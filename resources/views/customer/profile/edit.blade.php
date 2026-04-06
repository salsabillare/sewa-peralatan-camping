@extends('layouts.customer')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-400 to-purple-600 px-6 py-8">
            <h2 class="text-3xl font-bold text-white">Edit Profil</h2>
            <p class="text-purple-100 mt-1">Perbarui informasi akun Anda</p>
        </div>

        <!-- Form -->
        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <h3 class="font-bold mb-2"><i class="fa-solid fa-exclamation-triangle mr-2"></i>Terjadi Kesalahan</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-user text-purple-600 mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" name="name" 
                               value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-envelope text-purple-600 mr-2"></i>Email
                        </label>
                        <input type="email" name="email" 
                               value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-phone text-purple-600 mr-2"></i>Nomor Telepon
                        </label>
                        <input type="tel" name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role (Read-only) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fa-solid fa-shield text-purple-600 mr-2"></i>Tipe Akun
                        </label>
                        <input type="text" 
                               value="{{ ucfirst($user->role) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                               readonly>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fa-solid fa-map-pin text-purple-600 mr-2"></i>Alamat Lengkap
                    </label>
                    <textarea name="address" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600"
                              placeholder="Jl. Contoh No. 123, Kota, Provinsi"
                              required>{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
                        <i class="fa-solid fa-save"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.show') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                        <i class="fa-solid fa-times"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tips & Info -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-yellow-900 mb-3">
            <i class="fa-solid fa-lightbulb mr-2"></i>Tips Penting
        </h3>
        <ul class="text-yellow-800 text-sm space-y-2">
            <li><strong>Nama:</strong> Gunakan nama sesuai dengan identitas resmi Anda</li>
            <li><strong>Email:</strong> Gunakan email yang aktif untuk notifikasi penting</li>
            <li><strong>Telepon:</strong> Nomor yang dapat dihubungi untuk konfirmasi pesanan</li>
            <li><strong>Alamat:</strong> Isi dengan lengkap agar pengiriman tidak tersesat</li>
        </ul>
    </div>
</div>
@endsection
