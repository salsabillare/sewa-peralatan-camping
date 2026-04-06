@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')

<form action="{{ route('categories.update', $category->id) }}" method="POST" 
      class="bg-white p-6 rounded-lg shadow-md space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label for="id" class="block font-semibold text-purple-700 mb-2">ID Kategori</label>
        <input type="text" id="id" value="{{ $category->id }}" 
               class="w-full border border-purple-200 bg-purple-50 text-gray-600 rounded-lg p-2 cursor-not-allowed" 
               readonly>
    </div>

    <div>
        <label for="name" class="block font-semibold text-purple-700 mb-2">Nama Kategori</label>
        <input type="text" name="name" id="name" value="{{ $category->name }}" 
               class="w-full border border-purple-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-purple-400" 
               required>
    </div>

    <div>
        <label for="description" class="block font-semibold text-purple-700 mb-2">Deskripsi (Opsional)</label>
        <textarea name="description" id="description" rows="3" 
                  class="w-full border border-purple-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-purple-400">{{ $category->description }}</textarea>
    </div>

    <div class="flex space-x-3">
        <button type="submit" 
    class="bg-[#6d28d9] text-black px-4 py-2 rounded-lg hover:bg-[#5b21b6] transition font-semibold shadow-md">
    <i class="fas fa-save"></i> Update
</button>


<a href="{{ route('categories.index') }}" 
   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold shadow-md">
    <i class="fas fa-arrow-left"></i> Batal
</a>

    </div>
</form>
@endsection
