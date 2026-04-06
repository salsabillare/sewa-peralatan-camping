@extends('layouts.kasir')

@section('title', 'Orders Kasir')

@section('content')

<div style="background:#f8f5ff; padding:20px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div id="alert-success" style="
            padding:10px; 
            background-color:#e0d4ff; 
            color:#4b0082; 
            margin-bottom:15px; 
            border-radius:8px;
            transition: all 0.5s ease;
        ">
            {{ session('success') }}
        </div>
    @endif

    <table style="width:100%; border-collapse: collapse;">
        <thead style="background-color:#e9e0ff; color:#4b0082; font-weight:bold;">
            <tr>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Kode</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Pembeli</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Total</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Metode</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Status</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Status Pengembalian</th>
                <th style="padding:10px; border-bottom:2px solid #d3c3ff;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $order)
            <tr style="text-align:center;">
                <td style="padding:10px;">{{ $order->id }}</td>
                <td style="padding:10px;">{{ $order->user->name ?? 'Guest' }}</td>
                <td style="padding:10px;">Rp {{ number_format($order->total_price) }}</td>
                <td style="padding:10px;">{{ $order->payment_method }}</td>
                <td style="padding:10px;">
                    <span style="padding:5px 12px; background:#e0d4ff; color:#4b0082; border-radius:6px;">
                        {{ $order->status }}
                    </span>
                </td>
                <td style="padding:10px;">
                    @php
                        $totalItems = $order->items->count();
                        $returnedItems = $order->items->whereNotNull('returned_at')->count();
                        $notReturnedItems = $totalItems - $returnedItems;
                    @endphp
                    <div style="font-size:12px;">
                        @if($returnedItems > 0)
                            <span style="background:#d9fdd3; color:#2e7d32; padding:3px 8px; border-radius:4px; display:inline-block; margin-bottom:3px;">
                                ✓ Kembali: {{ $returnedItems }}/{{ $totalItems }}
                            </span>
                        @endif
                        @if($notReturnedItems > 0)
                            <span style="background:#fff3cd; color:#856404; padding:3px 8px; border-radius:4px; display:inline-block;">
                                ⏳ Belum: {{ $notReturnedItems }}/{{ $totalItems }}
                            </span>
                        @endif
                        @if($totalItems == 0)
                            <span style="color:#888;">-</span>
                        @endif
                    </div>
                </td>
                <td style="padding:10px; display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                    <a href="{{ route('kasir.orders.show', $order->id) }}" style="
                        background:#b090ff; 
                        color:white; 
                        padding:8px 12px; 
                        border-radius:8px; 
                        text-decoration:none;">
                        Detail
                    </a>
                    <a href="{{ route('kasir.orders.print', $order->id) }}" style="
                        background:#6b5b95; 
                        color:white; 
                        padding:8px 12px; 
                        border-radius:8px; 
                        text-decoration:none;"
                        target="_blank">
                        Cetak Struk
                    </a>
                    @if($notReturnedItems > 0)
                        <button onclick="openReturnModal({{ $order->id }}, {{ json_encode($order->items) }})" style="
                            background:#ff9800; 
                            color:white; 
                            padding:8px 12px; 
                            border-radius:8px; 
                            border:none;
                            cursor:pointer;">
                            Klaim Retur
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:15px;">Belum ada order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- Script fade out alert --}}
<script>
    setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if(alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000); // 3 detik

    // Modal untuk klaim retur dengan input tanggal dan denda
    function openReturnModal(orderId, items) {
        const notReturnedItems = items.filter(item => !item.returned_at);
        
        let itemsHtml = '<div style="margin-top:15px; max-height:400px; overflow-y:auto;">';
        notReturnedItems.forEach((item, index) => {
            itemsHtml += `
                <div style="padding:15px; background:#f9f9f9; border:1px solid #ddd; border-radius:6px; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; margin-bottom:10px;">
                        <input type="checkbox" name="returned_items" value="${item.id}" id="item_${item.id}" 
                               style="width:18px; height:18px; cursor:pointer; margin-right:10px;">
                        <label for="item_${item.id}" style="cursor:pointer; flex:1; font-weight:bold;">
                            ${item.product?.name || 'Produk'} x ${item.quantity}
                        </label>
                    </div>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:12px; margin-bottom:10px;">
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:4px;">📅 Tgl Kembali:</label>
                            <input type="date" class="returned_date" data-item="${item.id}" 
                                   value="${new Date().toISOString().split('T')[0]}"
                                   style="width:100%; padding:6px; border:1px solid #ccc; border-radius:4px;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:4px;">💰 Denda (Rp):</label>
                            <div style="display:flex; gap:5px;">
                                <input type="number" class="late_fee" data-item="${item.id}" value="0" min="0" step="1000"
                                       style="flex:1; padding:6px; border:1px solid #ccc; border-radius:4px;">
                                <button onclick="autoCalculateFee('${item.id}')" 
                                        style="background:#4b9bdb; color:white; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; font-size:11px; white-space:nowrap; font-weight:bold;"
                                        title="Hitung denda otomatis">
                                    🧮 Hitung
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="font-size:11px; color:#666; padding:8px; background:#f0f0f0; border-radius:4px; margin-bottom:8px;">
                        <strong>⏱️ Periode:</strong> ${item.rental_period || 24}jam | 
                        <strong>💵 Harga/hari:</strong> Rp ${parseInt(item.price).toLocaleString('id-ID')}
                    </div>
                    
                    <div style="font-size:11px; color:#2e7d32; padding:6px; background:#e8f5e9; border-radius:4px;">
                        <strong>✓ Durasi Sewa:</strong> ${(item.rental_period || 24) / 24} hari (Harus kembali sebelum ${new Date(new Date(item.created_at).getTime() + (item.rental_period || 24) * 3600000).toLocaleDateString('id-ID')})
                    </div>
                </div>
            `;
        });
        itemsHtml += '</div>';

        const modal = document.createElement('div');
        modal.id = 'returnModal';
        modal.style.cssText = `
            position:fixed; top:0; left:0; width:100%; height:100%; 
            background:rgba(0,0,0,0.5); display:flex; align-items:center; 
            justify-content:center; z-index:1000;
        `;
        
        modal.innerHTML = `
            <div style="background:white; padding:30px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.2); width:90%; max-width:700px;">
                <h3 style="margin:0 0 8px 0; color:#4b0082; font-size:18px;">📦 Klaim Barang Kembali</h3>
                <p style="color:#666; margin:0 0 15px 0; font-size:13px;">Isi tanggal kembali dan denda keterlambatan (jika ada). Klik "🧮 Hitung" untuk kalkulasi otomatis:</p>
                
                ${itemsHtml}
                
                <div style="display:flex; gap:10px; margin-top:20px; justify-content:flex-end;">
                    <button onclick="closeReturnModal()" style="
                        background:#ccc; color:#333; border:none; padding:10px 20px; 
                        border-radius:6px; cursor:pointer; font-weight:bold; transition:0.2s;"
                        onmouseover="this.style.background='#bbb'" onmouseout="this.style.background='#ccc'">
                        ❌ Batal
                    </button>
                    <button onclick="submitReturn(${orderId})" style="
                        background:#ff9800; color:white; border:none; padding:10px 20px; 
                        border-radius:6px; cursor:pointer; font-weight:bold; transition:0.2s;"
                        onmouseover="this.style.background='#e68900'" onmouseout="this.style.background='#ff9800'">
                        ✓ Konfirmasi
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
    }

    function autoCalculateFee(itemId) {
        const dateInput = document.querySelector(`.returned_date[data-item="${itemId}"]`);
        const returnedDate = dateInput?.value;
        
        if (!returnedDate) {
            alert('Pilih tanggal kembali terlebih dahulu!');
            return;
        }
        
        console.log('Sending request to: /kasir/orders/calculate-late-fee/' + itemId);
        console.log('Returned date:', returnedDate);
        
        fetch(`/kasir/orders/calculate-late-fee/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ returned_date: returnedDate })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                const feeInput = document.querySelector(`.late_fee[data-item="${itemId}"]`);
                feeInput.value = data.late_fee;
                alert(`✓ Denda dihitung: ${data.formatted}`);
            } else {
                alert('❌ Gagal menghitung denda: ' + (data.message || data));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat menghitung denda:\n' + error.message);
        });
    }

    function closeReturnModal() {
        const modal = document.getElementById('returnModal');
        if (modal) modal.remove();
    }

    function submitReturn(orderId) {
        const checkboxes = document.querySelectorAll('input[name="returned_items"]:checked');
        const itemIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (itemIds.length === 0) {
            alert('Pilih minimal satu barang untuk diklaim sebagai sudah kembali');
            return;
        }

        // Kumpulkan late fees dan returned dates
        const lateFees = {};
        const returnedDates = {};
        
        itemIds.forEach(itemId => {
            const dateInput = document.querySelector(`.returned_date[data-item="${itemId}"]`);
            const feeInput = document.querySelector(`.late_fee[data-item="${itemId}"]`);
            
            returnedDates[itemId] = dateInput?.value || new Date().toISOString().split('T')[0];
            lateFees[itemId] = parseFloat(feeInput?.value || 0);
        });

        fetch(`/kasir/orders/${orderId}/claim-return`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ 
                item_ids: itemIds,
                late_fees: lateFees,
                returned_dates: returnedDates
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Barang dan denda berhasil dicatat!');
                location.reload();
            } else {
                alert('❌ Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat mengirim data');
        });

        closeReturnModal();
    }
</script>

@endsection
