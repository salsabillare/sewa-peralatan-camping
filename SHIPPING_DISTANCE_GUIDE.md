# 📍 DOKUMENTASI SISTEM PENGIRIMAN BERBASIS JARAK
## (Radius Maksimal 10 km - Area Bantul & Sekitarnya)

## 📋 Overview

Sistem ini memungkinkan customer untuk:
1. **Melihat jarak mereka** dari toko (dalam km)
2. **Hanya bisa checkout** jika dalam radius 10 km
3. **Harga otomatis berubah** sesuai pilihan pengiriman

⚠️ **PENTING**: Layanan hanya tersedia untuk area Bantul dan sekitarnya dalam radius **maksimal 10 km** dari toko.

---

## 🔧 Setup & Configurasi

### 1. Migration Database
Jalankan migration untuk menambah kolom jarak ke tabel `shippings`:

```bash
php artisan migrate
```

Migration akan menambah:
- `min_distance`: Jarak minimum dalam km
- `max_distance`: Jarak maksimum dalam km

### 2. Jalankan Seeder
Seeder opsional untuk test data shipping:

```bash
php artisan db:seed --class=ShippingSeeder
```

Data yang di-seed (Hanya area 10km):
- Standar Lokal Bantul (0-10 km): Rp 15,000
- Express Lokal Bantul (0-10 km): Rp 25,000

---

## 🗺️ Cara Kerja Sistem

### Flow Checkout

```
Customer ke Checkout
    ↓
System baca alamat dari user profile
    ↓
DistanceService::getDistanceFromAddress()
    ↓
Convert alamat → Koordinat (Bantul & area sekitarnya)
    ↓
Haversine Formula: Hitung jarak (km)
    ↓
CEK JARAK:
  • Jika ≤ 10 km → Tampilkan opsi pengiriman ✓
  • Jika > 10 km → Reject dengan pesan error ✗
    ↓
Customer pilih shipping & lihat harga otomatis update
```

---

## 📍 DistanceService (App/Services/DistanceService.php)

### Method Utama:

#### 1. `calculateDistance($lat1, $lng1, $lat2 = null, $lng2 = null)`
Menghitung jarak menggunakan Haversine Formula.

```php
use App\Services\DistanceService;

$distance = DistanceService::calculateDistance(
    -6.2088,  // Latitude customer
    106.8456, // Longitude customer
    -6.9175,  // Latitude toko (default Jakarta)
    107.6191  // Longitude toko
);

echo $distance; // Output: 45.23 (km)
```

#### 2. `getCoordinatesFromAddress($address)`
Convert alamat → Koordinat (saat ini hardcoded untuk cities tertentu)

```php
$coords = DistanceService::getCoordinatesFromAddress('Bandung');
// Return: ['latitude' => -6.9175, 'longitude' => 107.6191]
```

#### 3. `getDistanceFromAddress($address)`
Shortcut: langsung dari alamat ke jarak (km)

```php
$distance = DistanceService::getDistanceFromAddress('Bandung');
// Return: 45.23 (float)
```

---

## 🎯 Hardcoded Cities (Area Bantul & Sekitarnya - Max 10km)

Saat ini mendukung:
- **Bantul** → -7.8847, 110.2929 (toko pusat)
- **Yogyakarta** → -7.8000, 110.3700
- **Sleman** → -7.6500, 110.4100
- **Depok** → -7.7972, 110.4167
- **Mertoyudan** → -7.8500, 110.3000
- **Kasihan** → -7.8600, 110.3400
- **Piyungan** → -7.8300, 110.3800
- **Sewon** → -7.9000, 110.3200

### Tambah Lebih Banyak Area Bantul:

Edit `app/Services/DistanceService.php`, cari section `$addressMappings`:

```php
$addressMappings = [
    'bantul' => ['latitude' => -7.8847, 'longitude' => 110.2929],
    'yogyakarta' => ['latitude' => -7.8000, 'longitude' => 110.3700],
    // TAMBAH AREA BARU DI SINI
    'modalan' => ['latitude' => -7.8600, 'longitude' => 110.2800],
    'wates' => ['latitude' => -7.9500, 'longitude' => 110.3000],
];
```

---

## 🔗 Integrasi Google Maps API (Optional)

Jika ingin lebih akurat, bisa integrasi dengan Google Maps Geocoding API:

### 1. Dapatkan API Key
- Buka [Google Cloud Console](https://console.cloud.google.com)
- Enable **Geocoding API**
- Buat API Key

### 2. Simpan di `.env`
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### 3. Update `config/services.php`
```php
'google' => [
    'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
],
```

### 4. Uncomment Code di DistanceService

Edit `app/Services/DistanceService.php`, cari method `getCoordinatesFromAddress()`:

Uncomment bagian **OPSI 2** dan comment bagian **OPSI 1**.

Perlu install package:
```bash
composer require guzzlehttp/guzzle
```

---

## 📊 Admin - Manage Shipping

Admin bisa manage shipping di `/shippings` (CRUD):

```php
// Controller: ShippingController
Route::resource('/shippings', ShippingController::class);
```

Form fields:
- **Name**: Nama metode (contoh: "Express Jakarta")
- **Cost**: Biaya pengiriman (dalam Rupiah)
- **Estimated Days**: Estimasi hari pengiriman
- **Description**: Deskripsi
- **Min Distance**: Jarak minimum (km)
- **Max Distance**: Jarak maksimum (km)

---

## 🛡️ Validasi & Error Handling

### Skenario 1: Customer di luar area 10km
```
⚠️ Sistem REJECT order dengan error message:
"Maaf, layanan pengiriman hanya tersedia untuk area Bantul 
dan sekitarnya dalam radius maksimal 10 km. Alamat Anda 
berada di luar jangkauan kami. Silakan hubungi customer service."
```

### Skenario 2: Customer punya alamat, tapi tidak terdeteksi
```
✓ Sistem tampilkan warning namun tetap berusaha hitung
⚠️ Tapi dengan warning bahwa jarak tidak pasti
👤 Customer diminta untuk memastikan alamat mereka dalam radius 10km
```

### Skenario 3: Customer memilih shipping, harga otomatis update
```
JavaScript di checkout view menghitung:
Total = Subtotal Produk + Biaya Pengiriman yang dipilih
```

---

## 💾 Database Schema

### Tabel: shippings
```sql
id              (bigint)
name            (string) - Nama metode pengiriman
cost            (decimal 10,2) - Biaya
estimated_days  (string) - Estimasi hari
description     (text nullable) - Deskripsi
min_distance    (integer) - Jarak minimum (km)
max_distance    (integer) - Jarak maksimum (km)
created_at      (timestamp)
updated_at      (timestamp)
```

---

## 🧪 Testing

### Test Data dengan Seeder
```bash
php artisan db:seed --class=ShippingSeeder
```

### Manual Test
1. Login sebagai customer
2. Ke halaman Checkout
3. Cek apakah:
   - ✓ Jarak terdeteksi
   - ✓ Hanya shipping sesuai jarak yang tampil
   - ✓ Harga otomatis update saat ganti shipping
   - ✓ Submit checkout berfungsi

---

## 🐛 Troubleshoot

### Problem: Jarak tidak terdeteksi
**Solusi**: 
- Pastikan User punya `address`
- Alamat harus cocok dengan hardcoded cities atau pakai Google Maps API
- Update alamat customer di profile

### Problem: Tidak ada shipping option yang tampil
**Solusi**:
- Pastikan ada shipping yang range-nya cocok dengan jarak
- Edit shipping di admin untuk adjust min/max distance
- Atau pakai seeder untuk reset data

### Problem: Harga tidak update saat ganti shipping
**Solusi**:
- Pastikan JavaScript di checkout view sudah dimuat
- Check browser console untuk error
- Buka di browser lain (clear cache)

---

## 📝 File-file yang Diubah/Dibuat

✅ **Created:**
- `/database/migrations/2026_02_02_000000_add_distance_to_shippings_table.php`
- `/app/Services/DistanceService.php`
- `/database/seeders/ShippingSeeder.php`
- `/dokumentasi/SHIPPING_DISTANCE_GUIDE.md` (file ini)

✅ **Updated:**
- `/app/Models/Shipping.php` - Tambah min_distance, max_distance di fillable
- `/app/Http/Controllers/OrderController.php` - Tambah logic hitung jarak & filter shipping
- `/resources/views/customer/checkout.blade.php` - Redesign UI dengan fitur jarak

---

## 🎨 UI/UX Features

✅ **Checkout Page sekarang punya:**
1. **Info Box Jarak** - Menampilkan jarak terdeteksi dengan icon
2. **Shipping Options Cantik** - Card layout dengan range jarak
3. **Ringkasan Harga Real-time** - Update otomatis saat pilih shipping
4. **Validasi & Warning Messages** - Jelas & user-friendly
5. **Mobile Responsive** - Layout CSS yang clean

---

## 🚀 Next Steps (Optional)

1. **Integrasi Google Maps API** - Lebih akurat
2. **Real-time Rate Calculation** - Dari toko real, bukan hardcoded
3. **Multi-toko Support** - Jika ada beberapa lokasi toko
4. **Shipping Partner API** - Integrasi dengan JNE, Pos, dll
5. **Customer Notification** - Email/SMS tracking pengiriman

---

## 📞 Support

Jika ada pertanyaan atau masalah, silakan cek:
1. Error message di halaman checkout
2. Browser console (F12 → Console tab)
3. Laravel logs di `/storage/logs/`

---

**Happy Shipping! 📦✨**
