@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h2 style="color:#4b0082; margin-bottom:20px;">Checkout</h2>

@if($cartItems->isEmpty())
    <p>Keranjang kosong. <a href="{{ route('shop.index') }}">Kembali ke toko</a></p>
@else
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <!-- INFO ALAMAT & JARAK -->
        <div style="background:#f0f7ff; padding:20px; border-radius:12px; border-left:4px solid #2196F3; margin-bottom:20px;">
            <h3 style="color:#1976D2; margin-top:0;">📍 Alamat Pengiriman & Jarak</h3>
            
            <div style="margin-bottom:15px;">
                <label for="address" style="display:block; margin-bottom:8px; font-weight:500;">Alamat Pengiriman</label>
                <textarea name="address" id="address" rows="3" required 
                    style="width:100%; padding:10px; border-radius:6px; border:1px solid #90CAF9; font-family:Arial;"
                    placeholder="Masukkan alamat lengkap (misal: Jakarta, Bandung, dll)">{{ $user->address }}</textarea>
                <small style="color:#666; display:block; margin-top:5px;">
                    💡 Tip: Masukkan nama kota agar sistem bisa menghitung jarak dengan akurat
                </small>
            </div>

            <!-- TAMPILKAN JARAK YANG TERDETEKSI -->
            @if($distance !== null)
                <div style="background:white; padding:12px; border-radius:6px; border-left:3px solid #4CAF50; margin-bottom:15px;">
                    <strong style="color:#2E7D32;">✓ Jarak Terdeteksi: {{ $distance }} km</strong>
                    <small style="display:block; color:#555; margin-top:3px;">
                        Sistem telah menghitung jarak dari alamat Anda ke lokasi toko
                    </small>
                </div>
            @else
                <div style="background:#FFF3E0; padding:12px; border-radius:6px; border-left:3px solid #FF9800; margin-bottom:15px;">
                    <strong style="color:#E65100;">⚠ Jarak Tidak Terdeteksi</strong>
                    <small style="display:block; color:#555; margin-top:3px;">
                        Sistem tidak bisa menghitung jarak dari alamat Anda. Pastikan alamat mencakup nama kota yang jelas.
                    </small>
                </div>
            @endif
        </div>

        <!-- PILIHAN SHIPPING -->
        <div style="background:#f3eaff; padding:20px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1); margin-bottom:20px;">
            <h3 style="color:#4b0082; margin-top:0;">🚚 Pilih Metode Pengiriman</h3>
            
            @if($shippings->isEmpty())
                <div style="background:#FFEBEE; padding:12px; border-radius:6px; border-left:3px solid #F44336;">
                    <strong style="color:#C62828;">❌ Tidak Ada Opsi Pengiriman Tersedia</strong>
                    <p style="margin:5px 0 0; color:#666;">
                        Maaf, tidak ada layanan pengiriman yang tersedia untuk jarak {{ $distance ?? 'Anda' }} km. 
                        Silakan update alamat Anda atau hubungi customer service.
                    </p>
                </div>
            @else
                <div style="background:#white; border:1px solid #E0E0E0; border-radius:6px;">
                    @foreach($shippings as $shipping)
                        <label style="display:block; padding:15px; border-bottom:1px solid #f0f0f0; cursor:pointer; transition:background 0.2s;">
                            <input type="radio" name="shipping_id" value="{{ $shipping->id }}" required 
                                style="margin-right:10px; width:18px; height:18px; cursor:pointer;" 
                                @if($loop->first) checked @endif>
                            
                            <span style="display:inline-block; flex:1;">
                                <strong style="color:#333;">{{ $shipping->name }}</strong>
                                <span style="color:#999; font-size:14px;"> 
                                    (Jarak: {{ $shipping->min_distance }}km - {{ $shipping->max_distance }}km)
                                </span>
                                <br>
                                <span style="color:#666; font-size:14px;">{{ $shipping->description }}</span>
                                <br>
                                <span style="color:#4CAF50; font-weight:bold; font-size:15px;">
                                    Rp {{ number_format($shipping->cost, 0, ',', '.') }}
                                </span>
                                <span style="color:#999; font-size:13px;"> 
                                    | Estimasi: {{ $shipping->estimated_days }} hari
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <!-- INFO SHIPPING -->
                @if($distance !== null)
                    <div style="background:#E8F5E9; padding:12px; border-radius:6px; margin-top:12px; border-left:3px solid #4CAF50;">
                        <small style="color:#2E7D32;">
                            ✓ Opsi pengiriman di atas sesuai dengan jarak {{ $distance }} km dari lokasi Anda.
                        </small>
                    </div>
                @else
                    <div style="background:#FFF3E0; padding:12px; border-radius:6px; margin-top:12px; border-left:3px solid #FF9800;">
                        <small style="color:#E65100;">
                            ⚠ Tidak bisa otomatis memvalidasi jarak. Pastikan pilih opsi yang sesuai alamat Anda.
                        </small>
                    </div>
                @endif
            @endif
        </div>

        <!-- RINGKASAN HARGA -->
        <div style="background:#f9f9f9; padding:20px; border-radius:12px; border:1px solid #e0e0e0; margin-bottom:20px;">
            <h3 style="color:#333; margin-top:0;">💰 Ringkasan Harga</h3>
            
            <div style="display:flex; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #e0e0e0;">
                <span>Subtotal Produk:</span>
                <strong>Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
            </div>
            
            <div style="display:flex; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #e0e0e0;">
                <span>Biaya Pengiriman:</span>
                <strong id="shipping-cost">-</strong>
            </div>
            
            <div style="display:flex; justify-content:space-between; font-size:18px;">
                <strong>Total:</strong>
                <strong style="color:#4b0082;">Rp <span id="total-price">{{ number_format($totalPrice, 0, ',', '.') }}</span></strong>
            </div>
        </div>

        @if(!$shippings->isEmpty())
            <button type="submit" style="padding:12px 20px; background:#7c4dff; color:white; border-radius:8px; border:none; font-size:16px; cursor:pointer; width:100%; font-weight:bold;">
                Bayar & Checkout
            </button>
        @endif
    </form>

    <!-- SCRIPT UPDATE HARGA SHIPPING -->
    <script>
        const subtotal = {{ $totalPrice }};
        const shippingOptions = {
            @foreach($shippings as $shipping)
                '{{ $shipping->id }}': {
                    cost: {{ $shipping->cost }},
                    name: '{{ $shipping->name }}'
                },
            @endforeach
        };

        function updateShippingPrice() {
            const selectedShippingId = document.querySelector('input[name="shipping_id"]:checked').value;
            const shippingCost = shippingOptions[selectedShippingId]?.cost || 0;
            const total = subtotal + shippingCost;

            document.getElementById('shipping-cost').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID', {minimumFractionDigits: 0});
            document.getElementById('total-price').textContent = total.toLocaleString('id-ID', {minimumFractionDigits: 0});
        }

        // Update harga saat halaman pertama kali load
        updateShippingPrice();

        // Update harga saat user mengubah pilihan shipping
        document.querySelectorAll('input[name="shipping_id"]').forEach(radio => {
            radio.addEventListener('change', updateShippingPrice);
        });
    </script>
@endif

@endsection
