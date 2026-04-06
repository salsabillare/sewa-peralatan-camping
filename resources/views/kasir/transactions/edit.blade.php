@extends('layouts.kasir')

@section('title', 'Edit Transaksi')

@section('content')
<div style="max-width:700px; margin:0 auto; background-color:#f8f5ff; padding:20px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="color:#4b0082; margin-bottom:20px; text-align:center;">Edit Transaksi</h2>

    <form action="{{ route('kasir.transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="products-container">
            @foreach($transaction->items as $index => $item)
            <div class="product-row" style="margin-bottom:15px; border-bottom:1px solid #d4b3ff; padding-bottom:10px;"
                 data-products='@json($products->map(fn($p)=>["id"=>$p->id,"name"=>$p->name,"price"=>$p->price]))'>
                
                <label style="color:#4b0082; font-weight:600;">Produk</label>
                <select name="products[{{ $index }}][product_id]" class="product-select" required
                    style="width:100%; padding:10px; border:1px solid #d4b3ff; border-radius:8px; background-color:white; margin-bottom:5px;">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - Rp{{ number_format($product->price,0,',','.') }}
                        </option>
                    @endforeach
                </select>

                <label style="color:#4b0082; font-weight:600;">Jumlah</label>
                <input type="number" name="products[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required
                    class="quantity-input"
                    style="width:100%; padding:10px; border:1px solid #d4b3ff; border-radius:8px;">

                <input type="hidden" class="price-input" value="{{ $item->price }}">
                <p style="margin:5px 0; font-weight:bold;">Subtotal: Rp<span class="subtotal-text">{{ number_format($item->subtotal,0,',','.') }}</span></p>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-product-btn"
            style="background-color:#c9a6ff; color:white; padding:8px 12px; border:none; border-radius:8px; margin-bottom:15px; cursor:pointer;">
            Tambah Produk
        </button>

        <!-- Total -->
        <div style="margin-bottom:15px;">
            <label style="color:#4b0082; font-weight:600;">Total</label>
            <input type="text" name="total" id="total" value="{{ $transaction->total }}" readonly
                style="width:100%; padding:10px; border-radius:8px; border:1px solid #c9a6ff; background-color:#e0d4ff; color:#4b0082; font-weight:bold;">
        </div>

        <!-- Uang Bayar -->
        <div style="margin-bottom:15px;">
            <label style="color:#4b0082; font-weight:600;">Uang Bayar</label>
            <input type="number" name="payment" id="payment" value="{{ $transaction->payment }}" min="0"
                style="width:100%; padding:10px; border-radius:8px; border:1px solid #c9a6ff;">
        </div>

        <!-- Kembalian -->
        <div style="margin-bottom:20px;">
            <label style="color:#4b0082; font-weight:600;">Kembalian</label>
            <input type="text" name="change" id="change" value="{{ $transaction->change }}" readonly
                style="width:100%; padding:10px; border-radius:8px; border:1px solid #c9a6ff; background-color:#e0d4ff; color:#4b0082; font-weight:bold;">
        </div>

        <!-- Tombol -->
        <div style="display:flex; justify-content:space-between;">
            <a href="{{ route('kasir.transactions.index') }}"
               style="background-color:#e0d4ff; color:#4b0082; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">
               Batal
            </a>

            <button type="submit"
                    style="background-color:#c9a6ff; color:white; padding:10px 20px; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
            const price = parseInt(row.querySelector('.price-input').value) || 0;
            const subtotal = quantity * price;
            row.querySelector('.subtotal-text').innerText = subtotal.toLocaleString('id-ID');
            total += subtotal;
        });
        document.getElementById('total').value = total.toLocaleString('id-ID');

        const payment = parseInt(document.getElementById('payment').value) || 0;
        document.getElementById('change').value = (payment - total > 0 ? payment - total : 0).toLocaleString('id-ID');
    }

    document.querySelectorAll('.quantity-input').forEach(input => input.addEventListener('input', updateTotals));
    document.getElementById('payment').addEventListener('input', updateTotals);

    document.getElementById('add-product-btn').addEventListener('click', function() {
        const container = document.getElementById('products-container');
        const index = container.querySelectorAll('.product-row').length;

        const div = document.createElement('div');
        div.classList.add('product-row');
        div.style.marginBottom = '15px';
        div.style.borderBottom = '1px solid #d4b3ff';
        div.style.paddingBottom = '10px';

        // Ambil data produk dari row pertama
        const firstRow = container.querySelector('.product-row');
        const productsData = JSON.parse(firstRow.dataset.products);

        let options = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            options += `<option value="${p.id}" data-price="${p.price}">${p.name} - Rp${p.price.toLocaleString('id-ID')}</option>`;
        });

        div.innerHTML = `
            <label style="color:#4b0082; font-weight:600;">Produk</label>
            <select name="products[${index}][product_id]" class="product-select" required
                style="width:100%; padding:10px; border:1px solid #d4b3ff; border-radius:8px; background-color:white; margin-bottom:5px;">
                ${options}
            </select>

            <label style="color:#4b0082; font-weight:600;">Jumlah</label>
            <input type="number" name="products[${index}][quantity]" value="1" min="1" required
                class="quantity-input"
                style="width:100%; padding:10px; border:1px solid #d4b3ff; border-radius:8px;">

            <input type="hidden" class="price-input" value="0">
            <p style="margin:5px 0; font-weight:bold;">Subtotal: Rp<span class="subtotal-text">0</span></p>
        `;

        container.appendChild(div);
        div.querySelector('.quantity-input').addEventListener('input', updateTotals);
    });

    updateTotals();
</script>
@endsection
