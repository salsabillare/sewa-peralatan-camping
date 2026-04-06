@extends('layouts.kasir')

@section('title', 'Tambah Transaksi')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 20px; color: #4b0082;">Tambah Transaksi Kasir</h1>

    @if(session('success'))
        <div style="padding: 10px; background-color: #e0d4ff; color: #4b0082; margin-bottom: 15px; border-radius: 8px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Form Input -->
        <div style="background-color: #f7f0ff; border-radius: 12px; padding: 20px;">
            <form action="{{ route('kasir.transactions.store') }}" method="POST" id="transactionForm">
                @csrf

                <!-- Pilih Produk -->
                <div style="margin-bottom: 15px;">
                    <label for="product_id" style="display: block; color: #4b0082; font-weight: bold; margin-bottom: 5px;">Pilih Produk</label>
                    <select id="product_id" style="width: 100%; padding: 8px; border: 1px solid #c9a6ff; border-radius: 8px; font-size: 14px;">
                        <option value="" data-price="0">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah -->
                <div style="margin-bottom: 15px;">
                    <label for="quantity" style="display: block; color: #4b0082; font-weight: bold; margin-bottom: 5px;">Jumlah</label>
                    <input type="number" id="quantity" value="1" min="1"
                           style="width: 100%; padding: 8px; border: 1px solid #c9a6ff; border-radius: 8px;">
                </div>

                <!-- Tombol Tambah -->
                <button type="button" id="addItem" 
                        style="width: 100%; background-color: #c9a6ff; color: white; font-weight: bold; padding: 10px; border: none; border-radius: 8px; cursor: pointer; margin-bottom: 20px; transition: 0.2s;">
                    ➕ Tambah ke Keranjang
                </button>

                <!-- Keranjang -->
                <div style="background-color: #e0d4ff; border-radius: 8px; padding: 15px;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #4b0082; margin-bottom: 10px;">🛒 Keranjang</h3>
                    <table id="cartTable" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #d4b5ff;">
                                <th style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; font-weight: bold; text-align: left;">Produk</th>
                                <th style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; font-weight: bold; text-align: center;">Harga</th>
                                <th style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; font-weight: bold; text-align: center;">Qty</th>
                                <th style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; font-weight: bold; text-align: right;">Subtotal</th>
                                <th style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; font-weight: bold; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <!-- Uang Bayar (Tersembunyi di dalam form) -->
                <input type="hidden" name="payment" id="paymentInput" value="0">
            </form>
        </div>

        <!-- Ringkasan & Pembayaran -->
        <div style="background-color: #f7f0ff; border-radius: 12px; padding: 20px; height: fit-content;">
            <h3 style="font-size: 18px; font-weight: bold; color: #4b0082; margin-bottom: 15px;">📋 Ringkasan Pembayaran</h3>

            <!-- Total -->
            <div style="border-bottom: 2px solid #e0d4ff; padding-bottom: 15px; margin-bottom: 15px;">
                <p style="color: #4b0082; margin-bottom: 5px; font-size: 13px;">Total Harga:</p>
                <p style="font-size: 28px; font-weight: bold; color: #c9a6ff;" id="totalDisplay">Rp 0</p>
            </div>

            <!-- Uang Bayar -->
            <div style="margin-bottom: 15px;">
                <label for="payment" style="display: block; color: #4b0082; font-weight: bold; margin-bottom: 5px;">Uang Bayar</label>
                <input type="number" id="payment" value="0" min="0"
                       style="width: 100%; padding: 8px; border: 1px solid #c9a6ff; border-radius: 8px; font-size: 16px; font-weight: bold;">
            </div>

            <!-- Kembalian -->
            <div style="background-color: #e0d4ff; border: 2px solid #c9a6ff; border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                <p style="color: #4b0082; font-size: 12px; margin-bottom: 5px;">💰 Kembalian:</p>
                <p style="font-size: 24px; font-weight: bold; color: #4b0082;" id="changeDisplay">Rp 0</p>
            </div>

            <!-- Status -->
            <div id="statusAlert" style="padding: 10px; border-radius: 8px; text-align: center; font-weight: bold; margin-bottom: 15px; display: none;"></div>

            <!-- Tombol Simpan -->
            <button form="transactionForm" type="submit" style="width: 100%; background-color: #7c4dff; color: white; font-weight: bold; padding: 12px; border: none; border-radius: 8px; cursor: pointer; transition: 0.2s; font-size: 16px;">
                ✅ Simpan Transaksi
            </button>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const addItemBtn = document.getElementById('addItem');
    const cartTable = document.getElementById('cartTable').querySelector('tbody');
    const totalDisplay = document.getElementById('totalDisplay');
    const paymentInput = document.getElementById('payment');
    const paymentHiddenInput = document.getElementById('paymentInput');
    const changeDisplay = document.getElementById('changeDisplay');
    const statusAlert = document.getElementById('statusAlert');

    let cart = [];

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
    }

    function renderCart() {
        cartTable.innerHTML = '';
        let total = 0;
        
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082;">${item.name}</td>
                <td style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; text-align: center;">${formatCurrency(item.price)}</td>
                <td style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; text-align: center; font-weight: bold;">${item.quantity}</td>
                <td style="border: 1px solid #c9a6ff; padding: 8px; color: #4b0082; text-align: right; font-weight: bold;">${formatCurrency(subtotal)}</td>
                <td style="border: 1px solid #c9a6ff; padding: 8px; text-align: center;">
                    <button type="button" onclick="removeItem(${index})" style="background-color: #ff6b6b; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-weight: bold;">🗑️ Hapus</button>
                </td>
            `;
            cartTable.appendChild(tr);
        });

        totalDisplay.textContent = formatCurrency(total);
        updateChange();
    }

    function updateChange() {
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const payment = parseInt(paymentInput.value) || 0;
        const change = payment - total;

        // Update hidden input untuk dikirim ke server
        paymentHiddenInput.value = payment;

        if (payment === 0) {
            changeDisplay.textContent = formatCurrency(0);
            statusAlert.style.display = 'none';
        } else if (payment < total) {
            changeDisplay.textContent = formatCurrency(0);
            statusAlert.style.display = 'block';
            statusAlert.textContent = '❌ Uang bayar kurang!';
            statusAlert.style.backgroundColor = '#ffcccc';
            statusAlert.style.color = '#ff0000';
        } else {
            changeDisplay.textContent = formatCurrency(change);
            statusAlert.style.display = 'block';
            statusAlert.textContent = '✅ Pembayaran cukup';
            statusAlert.style.backgroundColor = '#ccffcc';
            statusAlert.style.color = '#008000';
        }
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    addItemBtn.addEventListener('click', () => {
        const productId = productSelect.value;
        const productName = productSelect.options[productSelect.selectedIndex].text;
        const price = parseInt(productSelect.options[productSelect.selectedIndex].getAttribute('data-price'));
        const quantity = parseInt(quantityInput.value);

        if (!productId || quantity < 1) {
            alert('Pilih produk dan masukkan jumlah yang valid');
            return;
        }

        const existing = cart.find(item => item.id == productId);
        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.push({id: productId, name: productName, price: price, quantity: quantity});
        }

        productSelect.value = '';
        quantityInput.value = 1;
        renderCart();
    });

    paymentInput.addEventListener('input', updateChange);

    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Keranjang masih kosong!');
            return;
        }

        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        const payment = parseInt(paymentInput.value) || 0;

        if (payment < total) {
            e.preventDefault();
            alert('Uang bayar tidak cukup!');
            return;
        }

        cart.forEach((item, index) => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `products[${index}][product_id]`;
            inputId.value = item.id;
            this.appendChild(inputId);

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `products[${index}][quantity]`;
            inputQty.value = item.quantity;
            this.appendChild(inputQty);
        });
    });
</script>
@endsection
