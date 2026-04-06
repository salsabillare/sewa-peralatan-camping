<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk - Order #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .receipt-container {
            max-width: 400px;
            background-color: white;
            margin: 0 auto;
            padding: 20px;
            border: 2px dashed #333;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .shop-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 1px dashed #333;
            margin: 15px 0;
        }

        .receipt-info {
            margin-bottom: 15px;
            font-size: 13px;
            line-height: 1.8;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        .value {
            text-align: right;
            color: #555;
        }

        .items-section {
            margin: 15px 0;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #333;
            font-size: 12px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .item-name {
            flex: 1;
        }

        .item-qty {
            text-align: center;
            min-width: 35px;
        }

        .item-price {
            text-align: right;
            min-width: 80px;
        }

        .summary-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .summary-row.subtotal {
            color: #666;
        }

        .summary-row.shipping {
            color: #666;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 16px;
            color: #000;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #333;
        }

        .summary-label {
            font-weight: 600;
        }

        .summary-value {
            text-align: right;
        }

        .payment-method {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-size: 13px;
        }

        .payment-method .label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #333;
            font-size: 12px;
            color: #666;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .timestamp {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .receipt-container {
                max-width: 80mm;
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .print-button {
            display: block;
            margin: 20px auto;
            padding: 12px 30px;
            background-color: #6b5b95;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .print-button:hover {
            background-color: #5a4a84;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Cetak Struk</button>

    <div class="receipt-container">
        <div class="receipt-header">
            <div class="shop-name">CAMPGEAR HUB</div>
            <div style="font-size: 12px; color: #666;">Sewa Peralatan Camping</div>
        </div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="label">Kode Order:</span>
                <span class="value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Pembeli:</span>
                <span class="value">{{ $order->user->name ?? 'Guest' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="items-section">
            <div class="items-header">
                <span style="flex: 1;">Produk</span>
                <span style="text-align: center; min-width: 35px;">Qty</span>
                <span style="text-align: right; min-width: 80px;">Harga</span>
            </div>

            @forelse($order->items as $item)
                <div class="item">
                    <div class="item-name">
                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                    </div>
                    <div class="item-qty">{{ $item->quantity }}</div>
                    <div class="item-price">Rp {{ number_format($item->price) }}</div>
                </div>
            @empty
                <div class="item">
                    <div style="text-align: center; width: 100%; color: #999;">Tidak ada item</div>
                </div>
            @endforelse
        </div>

        <div class="summary-section">
            <div class="summary-row subtotal">
                <span class="summary-label">Subtotal:</span>
                <span class="summary-value">Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0)) }}</span>
            </div>
            @if($order->shipping_cost)
                <div class="summary-row shipping">
                    <span class="summary-label">Ongkos Kirim:</span>
                    <span class="summary-value">Rp {{ number_format($order->shipping_cost) }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span class="summary-label">Total:</span>
                <span class="summary-value">Rp {{ number_format($order->total_price) }}</span>
            </div>
        </div>

        <div class="payment-method">
            <div class="label">Metode Pembayaran</div>
            <div>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
        </div>

        <div class="receipt-footer">
            <div class="thank-you">Terima Kasih!</div>
            <div>Pesanan Anda sedang kami proses dengan baik.</div>
            <div>Keterlambatan pengembalian diatas waktu yang di tentukan akan di kenakan charge (+hari)</div>
            <div class="timestamp">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <script>
        // Auto print jika dibuka dari link
        // window.print();
    </script>
</body>
</html>
