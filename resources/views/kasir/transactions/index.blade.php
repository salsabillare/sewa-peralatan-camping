@extends('layouts.kasir')

@section('title', 'Daftar Transaksi')

@section('content')
    <div style="display:flex; justify-content:flex-end;">
        <a href="{{ route('kasir.transactions.create') }}" 
           style="background-color:#c9a6ff; color:white; font-weight:bold; border-radius:8px; padding:8px 16px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-decoration:none; transition:0.2s;">
           <i class="fas fa-plus"></i> Tambah Transaksi
        </a>
    </div>

    <div class="card-body" style="padding: 20px;">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #e9e0ff; color: #4b0082;">
                <tr>
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Kasir</th>
                    <th style="padding: 12px; text-align: left;">Produk</th>
                    <th style="padding: 12px; text-align: left;">Total</th>
                    <th style="padding: 12px; text-align: left;">Status Pengembalian</th>
                    <th style="padding: 12px; text-align: left;">Tanggal</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr style="border-bottom: 1px solid #ddd; transition: 0.2s;">
                        <td style="padding: 10px;">{{ $transaction->id }}</td>
                        <td style="padding: 10px;">{{ $transaction->user->name ?? '-' }}</td>
                        <td style="padding: 10px;">
                            @if($transaction->items->count() > 0)
                                <ul style="margin:0; padding-left:15px;">
                                    @foreach($transaction->items as $item)
                                        <li>{{ $item->product->name ?? '-' }} x {{ $item->quantity }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $transaction->product->name ?? '-' }} x {{ $transaction->quantity ?? '-' }}
                            @endif
                        </td>
                        <td style="padding: 10px;">Rp{{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td style="padding: 10px;">
                            @php
                                $totalItems = $transaction->items->count();
                                $returnedItems = $transaction->items->whereNotNull('returned_at')->count();
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
                        <td style="padding: 10px;">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td style="padding: 10px; text-align: center;">
                            {{-- Tombol Detail --}}
                            <a href="{{ route('kasir.transactions.show', $transaction->id) }}" 
                               style="background-color: #c9a6ff; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; margin-right: 5px;">
                               Detail
                            </a>

                            {{-- Tombol Cetak --}}
                            <a href="{{ route('kasir.transactions.print', $transaction->id) }}" target="_blank"
                               style="background-color: #b28dff; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; margin-right: 5px;">
                               Cetak
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('kasir.transactions.edit', $transaction->id) }}"
                               style="background-color: #a67efb; color: white; padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; margin-right: 5px;">
                               Edit
                            </a>

                            {{-- Tombol Klaim Retur --}}
                            @if($notReturnedItems > 0)
                                <button onclick="openReturnModal({{ $transaction->id }}, {{ json_encode($transaction->items) }})" style="
                                    background:#ff9800; color:white; border:none; padding:6px 12px; 
                                    border-radius:6px; font-size:13px; cursor:pointer; margin-right: 5px;">
                                    Klaim Retur
                                </button>
                            @endif

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('kasir.transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                        style="background-color:#d4b3ff; color:white; border:none; padding:6px 12px; border-radius:6px; font-size:13px; cursor:pointer;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888; padding: 15px;">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal dan Script untuk Klaim Retur --}}
    <script>
        // Modal untuk klaim retur
        function openReturnModal(transactionId, items) {
            const notReturnedItems = items.filter(item => !item.returned_at);
            
            let itemsHtml = '<div style="margin-top:15px;">';
            notReturnedItems.forEach((item, index) => {
                itemsHtml += `
                    <div style="display:flex; align-items:center; margin-bottom:10px; padding:10px; background:#f5f5f5; border-radius:6px;">
                        <input type="checkbox" name="returned_items" value="${item.id}" id="item_${item.id}" style="width:18px; height:18px; cursor:pointer; margin-right:10px;">
                        <label for="item_${item.id}" style="cursor:pointer; flex:1;">
                            <strong>${item.product?.name || 'Produk'}</strong> x ${item.quantity}
                        </label>
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
                <div style="background:white; padding:30px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.2); width:90%; max-width:500px;">
                    <h3 style="margin:0 0 15px 0; color:#4b0082;">Klaim Barang Kembali</h3>
                    <p style="color:#666; margin-bottom:15px;">Pilih barang mana saja yang sudah dikembalikan oleh customer:</p>
                    
                    ${itemsHtml}
                    
                    <div style="display:flex; gap:10px; margin-top:25px; justify-content:flex-end;">
                        <button onclick="closeReturnModal()" style="
                            background:#ccc; color:#333; border:none; padding:10px 20px; 
                            border-radius:6px; cursor:pointer; font-weight:bold;">
                            Batal
                        </button>
                        <button onclick="submitReturn(${transactionId})" style="
                            background:#ff9800; color:white; border:none; padding:10px 20px; 
                            border-radius:6px; cursor:pointer; font-weight:bold;">
                            Konfirmasi
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function closeReturnModal() {
            const modal = document.getElementById('returnModal');
            if (modal) modal.remove();
        }

        function submitReturn(transactionId) {
            const checkboxes = document.querySelectorAll('input[name="returned_items"]:checked');
            const itemIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (itemIds.length === 0) {
                alert('Pilih minimal satu barang untuk diklaim sebagai sudah kembali');
                return;
            }

            fetch(`/kasir/transactions/${transactionId}/claim-return`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ item_ids: itemIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Barang berhasil diklaim sebagai kembali!');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data');
            });

            closeReturnModal();
        }
    </script>
@endsection
