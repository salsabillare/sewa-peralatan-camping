@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')

<form action="{{ route('categories.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-4">
    @csrf

    <div>
        <label for="id" class="block font-semibold text-purple-700 mb-2">ID Kategori</label>
        <input type="text" id="id" value="(Akan dibuat otomatis)" 
               class="w-full border border-purple-200 bg-purple-50 text-gray-600 rounded-lg p-2 cursor-not-allowed" 
               readonly>
    </div>

    <div>
        <label for="name" class="block font-semibold text-purple-700 mb-2">Nama Kategori</label>
        <input type="text" name="name" id="name" 
               class="w-full border border-purple-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-purple-400" 
               required>
    </div>

    <div>
        <label for="description" class="block font-semibold text-purple-700 mb-2">Deskripsi (Opsional)</label>
        <textarea name="description" id="description" rows="3" 
                  class="w-full border border-purple-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-purple-400"></textarea>
    </div>

    <button type="submit" 
    class="bg-[#6d28d9] text-black px-4 py-2 rounded-lg hover:bg-[#5b21b6] transition font-semibold shadow-md">
    <i class="fas fa-save"></i> Simpan
</button>


</form>
@endsection
