@extends('layouts.customer')

@section('title', 'CampGear Hub - Sewa Peralatan Camping')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold mb-6 text-yellow-700">🏕️ Peralatan Camping</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-yellow-50 rounded-lg shadow hover:shadow-lg transition flex flex-col overflow-hidden">
                
                {{-- Gambar produk --}}
                <div class="relative">
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    
                    {{-- Badge diskon --}}
                    @if($product->discount)
                        <span class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 text-xs rounded">Diskon {{ $product->discount }}%</span>
                    @endif
                </div>

                {{-- Info produk --}}
                <div class="p-4 flex flex-col flex-1">
                    <h2 class="text-lg font-semibold text-yellow-900 mb-1">{{ $product->name }}</h2>

                    {{-- Rating bintang --}}
                    <div class="flex items-center mb-2">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 {{ $i < $product->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09L5.64 11.545 1 7.455l6.061-.545L10 1l2.939 5.91 6.061.545-4.64 4.09 1.518 6.545z" />
                            </svg>
                        @endfor
                        <span class="text-sm text-yellow-700 ml-2">({{ $product->reviews_count }})</span>
                    </div>

                    <p class="text-yellow-800 mb-3">
                        Rp {{ number_format($product->price) }}/hari
                    </p>

                    {{-- Form beli --}}
                    <form action="{{ route('shop.checkout') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="qty" value="1" min="1" class="w-full mb-2 border rounded p-1 text-yellow-900">
                        <button type="submit" class="w-full bg-yellow-500 text-white rounded py-2 hover:bg-yellow-600 transition">
                            Sewa Sekarang
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-purple-900 col-span-full text-center mt-6">Belum ada produk tersedia di CampGear Hub.</p>
        @endforelse
    </div>
</div>
@endsection
