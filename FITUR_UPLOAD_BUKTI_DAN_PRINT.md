# 📋 Fitur Baru: Upload Bukti Pembayaran & Print Pesanan

## 🎯 Fitur yang Ditambahkan

### 1️⃣ **Upload Bukti Pembayaran (Customer)**

**Lokasi:** Halaman detail pesanan customer (`/my-orders/{order}`)

**Fitur:**
- ✅ Customer dapat upload bukti transfer/pembayaran dalam format **JPG, PNG** (max 5MB)
- ✅ **Drag & Drop** support - taruh file ke area atau klik untuk browse
- ✅ **Preview gambar** sebelum upload
- ✅ Tampilkan bukti yang sudah diupload dengan opsi hapus
- ✅ File disimpan di folder `storage/app/public/payment_proofs/order_{id}/`

**User Flow:**
1. Customer membuka detail pesanan dengan status pembayaran "Menunggu"
2. Setelah transfer, customer upload bukti transfer via form upload
3. File disimpan dan dapat dilihat di preview
4. Admin dapat lihat bukti langsung di halaman detail pesanan admin

---

### 2️⃣ **Print Pesanan (Customer)**

**Lokasi:** Tombol "Cetak Pesanan" di halaman detail pesanan customer

**Fitur:**
- ✅ Customer dapat print pesanan dalam format A4-friendly
- ✅ Menampilkan: informasi order, detail produk, harga, alamat pengiriman
- ✅ Tombol dan form otomatis tersembunyi saat print
- ✅ Customer bisa print ke PDF atau langsung ke printer

**Print Include:**
- 📋 Nomor pesanan
- 👤 Informasi customer (nama, email, telepon)
- 📦 Detail produk dan harga
- 💰 Total pembayaran & ongkos kirim
- 📍 Alamat pengiriman
- 📊 Status pesanan
- 💳 Metode pembayaran

---

## 🔧 Perubahan Technical

### Database
```sql
-- Column baru di tabel orders
ALTER TABLE orders ADD COLUMN payment_proof VARCHAR(255) NULLABLE;
```

### Model (Order.php)
- Tambah `'payment_proof'` ke fillable array

### Controller (OrderController.php)
**Method Baru:**

#### `uploadProof(Request $request, Order $order)`
- Validasi file image (JPEG, PNG, max 5MB)
- Hapus file lama jika ada
- Store file ke `storage/app/public/payment_proofs/order_{id}/`
- Update field `payment_proof` di database
- Return success message

#### `deleteProof(Order $order)`
- Hapus file dari storage
- Set `payment_proof` ke null
- Return success message

### Routes (web.php)
```php
Route::post('/my-orders/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('customer.orders.uploadProof');
Route::delete('/my-orders/{order}/delete-proof', [OrderController::class, 'deleteProof'])->name('customer.orders.deleteProof');
```

### Views

#### Customer Orders Show (customer/orders/show.blade.php)
- ✅ Form upload dengan drag & drop
- ✅ Preview image sebelum/sesudah upload
- ✅ Tombol print pesanan
- ✅ JavaScript handle file preview dan drag & drop

#### Admin Orders Show (admin/orders/show.blade.php)
- ✅ Tampilkan bukti pembayaran customer di section pembayaran
- ✅ Link download untuk lihat ukuran penuh

---

## 📝 File Storage Path

```
storage/
└── app/
    └── public/
        └── payment_proofs/
            ├── order_1/
            │   ├── bukti_transfer.jpg
            │   └── ...
            ├── order_2/
            │   ├── bukti_transfer.png
            │   └── ...
            └── ...
```

**Accessing file:**
```php
asset('storage/' . $order->payment_proof)
```

---

## 🔐 Security

✅ **Authorization Check:**
- Customer hanya bisa upload untuk order mereka sendiri
- Admin dapat melihat semua bukti pembayaran
- File validation: image only, max 5MB

✅ **File Protection:**
- Simpan di `public` disk (accessible via web)
- Setiap order terpisah folder untuk organisasi
- Automatic cleanup kalo customer update file

---

## 🚀 Cara Menggunakan

### Untuk Customer:

**Upload Bukti:**
1. Buka halaman "Detail Pesanan"
2. Scroll ke section "Upload Bukti Pembayaran"
3. Drag file atau klik "Upload"
4. Pilih file JPG/PNG (< 5MB)
5. Klik "Upload Bukti Pembayaran"
6. Tunggu konfirmasi admin

**Print Pesanan:**
1. Klik tombol "Cetak Pesanan" (icon printer)
2. Browser print dialog muncul
3. Pilih "Save as PDF" atau printer fisik
4. Download atau print

### Untuk Admin:

**Review Bukti:**
1. Buka halaman "Daftar Pesanan"
2. Klik pesanan dengan pembayaran "Menunggu"
3. Lihat section "Status Pembayaran"
4. Bukti pembayaran ditampilkan dengan preview
5. Klik "Lihat Ukuran Penuh" untuk detail
6. Approve atau reject pembayaran

---

## ✅ Testing Checklist

- [ ] Migration berhasil di-apply
- [ ] Customer dapat upload bukti (test drag & drop, click upload)
- [ ] Preview image muncul
- [ ] File tersimpan di storage
- [ ] Admin dapat melihat bukti pembayaran
- [ ] Customer dapat hapus bukti
- [ ] Customer dapat print pesanan
- [ ] File validation berfungsi (reject file non-image, > 5MB)

---

## 📌 Notes

- File disimpan dalam format: `payment_proofs/order_{order_id}/filename.ext`
- Jika customer upload file baru, file lama otomatis dihapus
- Print optimize untuk ukuran A4
- Responsive design untuk mobile

