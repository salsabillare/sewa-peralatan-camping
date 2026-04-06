@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    {{-- Alert untuk Quick Checkout --}}
    @if($isQuickCheckout)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-info-circle text-blue-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <strong>Checkout Cepat:</strong> Anda membeli produk ini secara langsung. Item yang ditampilkan di bawah hanya produk yang dipilih dan tidak termasuk item yang ada di keranjang Anda.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Pengiriman -->
        <div class="lg:col-span-2">
            <!-- Informasi Pengiriman -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-6 flex items-center">
                    <i class="fa-solid fa-map-pin mr-2"></i>
                    Alamat Pengiriman
                </h2>

                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Nama -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama Penerima</label>
                            <input type="text" name="name" value="{{ $user->name }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-600"
                                   required>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">No Telepon</label>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-600"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-600"
                                  placeholder="Jl. Contoh No. 123, Bantul / Yogyakarta"
                                  required>{{ $user->address ?? '' }}</textarea>
                        <small class="text-gray-600 text-xs mt-2 block">
                            💡 Tip: Pastikan alamat Anda jelas dan lengkap. Kurir akan menghubungi anda untuk meminta sharelock/ciri lokasi pengiriman.
                        </small>
                    </div>

                    <!-- Durasi Sewa -->
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-clock mr-2"></i>
                        Durasi Sewa Peralatan
                    </h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800 mb-4">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Pilih berapa lama Anda ingin menyewa. Setiap penambahan 24 jam akan menambah biaya dengan jumlah yang sama.
                        </p>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            @php
                                $rentalPeriods = [24, 48, 72, 96, 120];
                            @endphp
                            
                            @foreach($rentalPeriods as $period)
                            @php
                                $days = $period / 24;
                                $multiplier = $days;
                            @endphp
                            <label class="flex flex-col items-center gap-2 cursor-pointer p-3 border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-100 transition" id="period_{{ $period }}">
                                <input type="radio" name="rental_period" value="{{ $period }}" class="w-4 h-4" 
                                       {{ $loop->first ? 'checked' : '' }} onchange="updatePriceDisplay()">
                                <span class="font-bold text-gray-800">{{ $period }} Jam</span>
                                <span class="text-xs text-gray-600">({{ $days }}{{ $days == 1 ? ' hari' : ' hari' }})</span>
                                <span class="text-xs font-semibold text-blue-600">{{ $multiplier }}x</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-box mr-2"></i>
                        Detail Pesanan
                    </h2>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-4 py-4 border-b last:border-b-0">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded"></div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}/24jam 
                                    <span class="text-blue-600 font-semibold rental-multiplier" data-base-price="{{ $item->product->price }}">× 1 = Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Qty: {{ $item->quantity }}</p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold item-total" data-base-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">per {{ $item->quantity }}x</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Jaminan Identitas -->
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-id-card mr-2"></i>
                        Jaminan Identitas
                    </h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800 mb-4">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Silakan pilih jenis jaminan identitas dan masukkan nomornya. Identitas akan dibawa kurir sebagai jaminan dan dapat diambil kembali saat mengembalikan peralatan.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Jenis Jaminan -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Jenis Jaminan *</label>
                                <select name="guarantee_type" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-600"
                                        required>
                                    <option value="">-- Pilih Jenis Jaminan --</option>
                                    <option value="ktp">Kartu Tanda Penduduk (KTP)</option>
                                    <option value="sim">Surat Izin Mengemudi (SIM)</option>
                                    <option value="passport">Paspor</option>
                                    <option value="kartu_pelajar">Kartu Pelajar/Mahasiswa</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <!-- Nomor Jaminan -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Nomor Identitas *</label>
                                <input type="text" name="guarantee_number" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-600"
                                       placeholder="cth: 1234567890123456"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pengiriman - Pilihan Customer -->
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-truck mr-2"></i>
                        Pilih Metode Pengiriman
                    </h2>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                        @forelse($shippings as $shipping)
                        <label class="flex items-start gap-3 cursor-pointer p-3 border border-gray-200 rounded hover:bg-blue-50 hover:border-blue-400 transition">
                            <input type="radio" name="shipping_id" value="{{ $shipping->id }}" class="w-4 h-4 mt-1" required onchange="updateTotal()">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-box text-blue-600"></i>{{ $shipping->name }}
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $shipping->description }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm text-gray-700">Estimasi: {{ $shipping->estimated_days }}</span>
                                    <span class="text-lg font-bold text-blue-600">Rp {{ number_format($shipping->cost, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
                            <i class="fa-solid fa-exclamation-triangle mr-2"></i>Belum ada metode pengiriman yang tersedia
                        </div>
                        @endforelse
                    </div>

                    <!-- Metode Pembayaran -->
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fa-solid fa-credit-card mr-2"></i>
                        Metode Pembayaran
                    </h2>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="transfer" checked class="w-4 h-4">
                            <span>
                                <i class="fa-solid fa-bank text-blue-600 mr-1"></i>Transfer Bank
                            </span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="cash" class="w-4 h-4">
                            <span>
                                <i class="fa-solid fa-money-bill text-green-600 mr-1"></i>Tunai (Cash)
                            </span>
                        </label>
                    </div>

                </form>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>

                <div class="space-y-4 mb-6">
                    @php
                        $subtotal = $cartItems->sum(function($item) {
                            return $item->product->price * $item->quantity;
                        });
                    @endphp

                    <div class="flex justify-between text-sm">
                        <span>Subtotal:</span>
                        <span id="subtotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span>Ongkos Kirim:</span>
                        <span id="shippingDisplay" class="text-orange-600 font-semibold">Rp 0</span>
                    </div>

                    <div class="border-t pt-4 flex justify-between font-bold text-lg">
                        <span>Total Pembayaran:</span>
                        <span class="text-blue-600" id="totalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" form="checkoutForm" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition">
                    <i class="fa-solid fa-check mr-2"></i> Buat Pesanan
                </button>

                <a href="{{ route('cart.index') }}" class="block text-center mt-3 text-blue-600 hover:text-blue-800">
                    Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const shippings = {!! json_encode($shippings->pluck('cost', 'id')) !!};
    
    @php
        $cartItemsArray = [];
        foreach($cartItems as $item) {
            $cartItemsArray[] = [
                'price' => $item->product->price,
                'quantity' => $item->quantity
            ];
        }
    @endphp
    
    const cartItems = {!! json_encode($cartItemsArray) !!};

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }

    function getRentalPeriod() {
        const selected = document.querySelector('input[name="rental_period"]:checked');
        return selected ? parseInt(selected.value) : 24;
    }

    function getMultiplier() {
        const rentalPeriod = getRentalPeriod();
        return rentalPeriod / 24;
    }

    function updatePriceDisplay() {
        const multiplier = getMultiplier();
        const rentalPeriod = getRentalPeriod();
        
        // Update individual item prices
        const multiplierElements = document.querySelectorAll('.rental-multiplier');
        multiplierElements.forEach(el => {
            const basePrice = parseInt(el.dataset.basePrice);
            const multipliedPrice = basePrice * multiplier;
            el.innerHTML = `× ${multiplier} = Rp ${formatRupiah(multipliedPrice)}`;
        });
        
        // Update item totals
        const itemTotals = document.querySelectorAll('.item-total');
        itemTotals.forEach(el => {
            const basePrice = parseInt(el.dataset.basePrice);
            const quantity = parseInt(el.dataset.quantity);
            const total = basePrice * multiplier * quantity;
            el.textContent = `Rp ${formatRupiah(total)}`;
        });
        
        // Update rental period labels styling
        document.querySelectorAll('input[name="rental_period"]').forEach(input => {
            const label = input.closest('label');
            if (input.checked) {
                label.classList.remove('border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-100');
                label.classList.add('border-blue-600', 'bg-blue-100', 'shadow-lg');
            } else {
                label.classList.add('border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-100');
                label.classList.remove('border-blue-600', 'bg-blue-100', 'shadow-lg');
            }
        });
        
        updateTotal();
    }

    function updateTotal() {
        const multiplier = getMultiplier();
        let subtotal = 0;
        
        cartItems.forEach(item => {
            subtotal += item.price * item.quantity * multiplier;
        });
        
        const shippingRadio = document.querySelector('input[name="shipping_id"]:checked');
        const shippingId = shippingRadio ? shippingRadio.value : null;
        const shippingCost = shippingId && shippings[shippingId] ? parseInt(shippings[shippingId]) : 0;
        const total = subtotal + shippingCost;
        
        document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('shippingDisplay').textContent = 'Rp ' + formatRupiah(shippingCost);
        document.getElementById('totalDisplay').textContent = 'Rp ' + formatRupiah(total);
    }

    // Initial call
    updatePriceDisplay();
</script>
@endsection
