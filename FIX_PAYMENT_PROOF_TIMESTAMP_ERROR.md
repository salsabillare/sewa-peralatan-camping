# 🔧 Fix Error: Payment Proof Timestamp Issue

## ❌ Masalah
```
League\Flysystem\FileNotFoundException
Tidak dapat mengambil nilai last_modified untuk file di lokasi: 
public/payment_proofs/order_7/EuTMD8yjckKSOMpVKjkq963kLvekXddMJVVGZwzi.png
```

**Penyebab:** 
- Fungsi `Storage::lastModified()` tidak dapat mengakses metadata file dengan reliable
- File storage tidak selalu sync dengan filesystem metadata
- Ini adalah issue umum di Laravel dengan public storage

---

## ✅ Solusi yang Diterapkan

### 1. **Tambah Field Baru di Database**

**Migration baru:**
```php
2026_04_08_000001_add_payment_proof_uploaded_at_to_orders_table.php

Schema::table('orders', function (Blueprint $table) {
    $table->timestamp('payment_proof_uploaded_at')->nullable();
});
```

**Keuntungan:**
- ✅ Timestamp tersimpan di database (reliable)
- ✅ Tidak depend on file system metadata
- ✅ Lebih cepat saat query
- ✅ Trackable dan auditable

---

### 2. **Update Model Order**

**File:** `app/Models/Order.php`

```php
protected $fillable = [
    // ... other fields
    'payment_proof',
    'payment_proof_uploaded_at',  // ← BARU
];

protected $casts = [
    'payment_proof_uploaded_at' => 'datetime',  // ← BARU
    // ... other casts
];
```

---

### 3. **Update Controller**

**File:** `app/Http/Controllers/OrderController.php`

**Method `uploadProof()`:**
```php
public function uploadProof(Request $request, Order $order)
{
    // ... validation & file upload ...
    
    $order->update([
        'payment_proof' => $path,
        'payment_proof_uploaded_at' => now(),  // ← SET TIMESTAMP
    ]);
}
```

---

### 4. **Update Views**

#### Admin View: `admin/orders/show.blade.php`
```blade
<!-- SEBELUM (ERROR) -->
<strong>Diupload:</strong> 
{{ \Storage::lastModified('public/' . $order->payment_proof) ? 
   date('d M Y H:i', \Storage::lastModified(...)) : 'Unknown' }}

<!-- SESUDAH (FIXED) -->
@if($order->payment_proof_uploaded_at)
<strong>Diupload:</strong> {{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}
@endif
```

#### Customer View: `customer/orders/show.blade.php`
```blade
@if($order->payment_proof_uploaded_at)
<p class="text-sm text-green-700 mb-2">
    Diupload: {{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}
</p>
@endif
```

---

## 📋 Perubahan File

| File | Perubahan |
|------|-----------|
| `2026_04_08_000001_*.php` | Migration baru: tambah `payment_proof_uploaded_at` |
| `app/Models/Order.php` | Tambah field di `$fillable` & `$casts` |
| `app/Http/Controllers/OrderController.php` | Set timestamp saat upload: `'payment_proof_uploaded_at' => now()` |
| `admin/orders/show.blade.php` | Ganti `Storage::lastModified()` dengan `$order->payment_proof_uploaded_at` |
| `customer/orders/show.blade.php` | Tampilkan timestamp dari field baru |

---

## ✨ Hasil

✅ **Error resolved** - Tidak ada lagi file metadata error  
✅ **Reliable timestamp** - Timestamp dari database, bukan file system  
✅ **Better performance** - Query lebih cepat tanpa access file system  
✅ **Auditable** - Bisa track kapan file diupload  
✅ **Backward compatible** - File lama tetap work (timestamp null sampai re-upload)  

---

## 🚀 Testing

```bash
# Run migration
php artisan migrate

# Test upload
1. Login sebagai customer
2. Ke halaman order detail
3. Upload bukti pembayaran
4. Check admin view - harus tampil timestamp

# Expected output
✅ Bukti Pembayaran dari Customer
✅ File: [filename].png
✅ Diupload: 08 Apr 2026 14:30
✅ Link "Lihat Ukuran Penuh" working
```

---

## 📌 Migration Status

```
Running migrations...
2026_04_08_000001_add_payment_proof_uploaded_at_to_orders_table ....... DONE ✅
```

---

## 🔒 Security Notes

- ✅ No breaking change untuk existing files
- ✅ Timestamp only set on new uploads (tidak backfil existing)
- ✅ Can be null untuk files uploaded sebelum fix
- ✅ Optional handling di view dengan `@if` check

