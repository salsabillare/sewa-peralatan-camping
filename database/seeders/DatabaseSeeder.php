<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Admin
        User::factory()->create([
            'name' => 'Admin CampGear',
            'email' => 'admin@campgear.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // User Kasir
        User::factory()->create([
            'name' => 'Kasir CampGear',
            'email' => 'kasir@campgear.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        // User Pelanggan
        User::factory()->create([
            'name' => 'Pelanggan Test',
            'email' => 'customer@campgear.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // ==================== KATEGORI PERALATAN CAMPING ====================
        $tent = Category::create([
            'name' => 'Tenda',
            'description' => 'Berbagai jenis tenda untuk camping'
        ]);

        $sleeping = Category::create([
            'name' => 'Perlengkapan Tidur',
            'description' => 'Sleeping bag, matras, dan perlengkapan tidur'
        ]);

        $cooking = Category::create([
            'name' => 'Peralatan Memasak',
            'description' => 'Kompor, panci, dan peralatan dapur outdoor'
        ]);

        $backpack = Category::create([
            'name' => 'Tas & Backpack',
            'description' => 'Tas punggung dan tas outdoor'
        ]);

        $light = Category::create([
            'name' => 'Pencahayaan',
            'description' => 'Lampu, senter, dan penerangan outdoor'
        ]);

        $safety = Category::create([
            'name' => 'Keselamatan & Tools',
            'description' => 'Peralatan keselamatan dan tools outdoor'
        ]);

        // ==================== PRODUK TENDA ====================
        Product::create([
            'category_id' => $tent->id,
            'name' => 'Tenda Dome 2 Orang',
            'sku' => 'TENT-001',
            'price' => 18000,
            'stock' => 15,
            'description' => 'Tenda dome berkualitas untuk 2 orang, tahan air dan tahan angin',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $tent->id,
            'name' => 'Tenda Dome 4 Orang',
            'sku' => 'TENT-002',
            'price' => 25000,
            'stock' => 12,
            'description' => 'Tenda dome besar untuk 4 orang dengan ventilasi baik',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $tent->id,
            'name' => 'Tenda Tunnel 6 Orang',
            'sku' => 'TENT-003',
            'price' => 40000,
            'stock' => 8,
            'description' => 'Tenda tunnel spacious untuk 6 orang, cocok untuk keluarga',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $tent->id,
            'name' => 'Tenda Ultralight Solo',
            'sku' => 'TENT-004',
            'price' => 18000,
            'stock' => 20,
            'description' => 'Tenda ringan untuk solo camping, mudah dibawa',
            'image' => null,
        ]);

        // ==================== PRODUK PERLENGKAPAN TIDUR ====================
        Product::create([
            'category_id' => $sleeping->id,
            'name' => 'Sleeping Bag Musim Dingin',
            'sku' => 'SLEEP-001',
            'price' => 8000,
            'stock' => 25,
            'description' => 'Sleeping bag thermal untuk cuaca dingin',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $sleeping->id,
            'name' => 'Sleeping Bag Musim Panas',
            'sku' => 'SLEEP-002',
            'price' => 5000,
            'stock' => 30,
            'description' => 'Sleeping bag ringan untuk musim panas',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $sleeping->id,
            'name' => 'Matras Camping Eva',
            'sku' => 'SLEEP-003',
            'price' => 5000,
            'stock' => 35,
            'description' => 'Matras eva foam, anti air dan mudah dibersihkan',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $sleeping->id,
            'name' => 'Matras Inflatable Premium',
            'sku' => 'SLEEP-004',
            'price' => 15000,
            'stock' => 15,
            'description' => 'Matras udara premium dengan pompa built-in',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $sleeping->id,
            'name' => 'Bantal Camping Inflatable',
            'sku' => 'SLEEP-005',
            'price' => 8000,
            'stock' => 20,
            'description' => 'Bantal inflatable kompak untuk camping',
            'image' => null,
        ]);

        // ==================== PRODUK PERALATAN MEMASAK ====================
        Product::create([
            'category_id' => $cooking->id,
            'name' => 'Kompor Portable Gas',
            'sku' => 'COOK-001',
            'price' => 15000,
            'stock' => 18,
            'description' => 'Kompor portable dengan fuel hemat',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $cooking->id,
            'name' => 'Panci Set Camping',
            'sku' => 'COOK-002',
            'price' => 25000,
            'stock' => 14,
            'description' => 'Set panci lengkap untuk memasak outdoor',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $cooking->id,
            'name' => 'Thermos Stainless Steel',
            'sku' => 'COOK-003',
            'price' => 12000,
            'stock' => 22,
            'description' => 'Thermos 1.5L untuk minuman panas/dingin',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $cooking->id,
            'name' => 'Kompor Alcohol Stove',
            'sku' => 'COOK-004',
            'price' => 3000,
            'stock' => 40,
            'description' => 'Kompor alkohol ultra ringan dan praktis',
            'image' => null,
        ]);

        // ==================== PRODUK TAS & BACKPACK ====================
        Product::create([
            'category_id' => $backpack->id,
            'name' => 'Backpack 40L Hiking',
            'sku' => 'BAG-001',
            'price' => 35000,
            'stock' => 16,
            'description' => 'Backpack 40 liter dengan frame aluminum',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $backpack->id,
            'name' => 'Backpack 60L Trekking',
            'sku' => 'BAG-002',
            'price' => 50000,
            'stock' => 12,
            'description' => 'Backpack besar 60L untuk perjalanan panjang',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $backpack->id,
            'name' => 'Backpack 25L Day Pack',
            'sku' => 'BAG-003',
            'price' => 20000,
            'stock' => 20,
            'description' => 'Daypack ringkas 25L untuk short trip',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $backpack->id,
            'name' => 'Tas Tahan Air Waterproof',
            'sku' => 'BAG-004',
            'price' => 15000,
            'stock' => 18,
            'description' => 'Tas waterproof untuk melindungi barang berharga',
            'image' => null,
        ]);

        // ==================== PRODUK PENCAHAYAAN ====================
        Product::create([
            'category_id' => $light->id,
            'name' => 'Headlamp LED',
            'sku' => 'LIGHT-001',
            'price' => 7000,
            'stock' => 28,
            'description' => 'Lampu kepala LED untuk trekking malam',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $light->id,
            'name' => 'Senter Tactical LED',
            'sku' => 'LIGHT-002',
            'price' => 8000,
            'stock' => 25,
            'description' => 'Senter LED powerful dengan baterai tahan lama',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $light->id,
            'name' => 'Camping Lantern LED',
            'sku' => 'LIGHT-003',
            'price' => 9000,
            'stock' => 18,
            'description' => 'Lampu tenda LED yang dapat diredupkan',
            'image' => null,
        ]);

        // ==================== PRODUK KESELAMATAN & TOOLS ====================
        Product::create([
            'category_id' => $safety->id,
            'name' => 'Multi Tool Knife',
            'sku' => 'SAFE-001',
            'price' => 12000,
            'stock' => 22,
            'description' => 'Multi tool outdoor dengan berbagai fungsi',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $safety->id,
            'name' => 'First Aid Kit Camping',
            'sku' => 'SAFE-002',
            'price' => 20000,
            'stock' => 19,
            'description' => 'Kit pertolongan pertama lengkap untuk outdoor',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $safety->id,
            'name' => 'Rope 50m Nylon',
            'sku' => 'SAFE-003',
            'price' => 10000,
            'stock' => 24,
            'description' => 'Tali nylon 50m untuk berbagai keperluan',
            'image' => null,
        ]);

        Product::create([
            'category_id' => $safety->id,
            'name' => 'Carabiner Set',
            'sku' => 'SAFE-004',
            'price' => 15000,
            'stock' => 26,
            'description' => 'Set carabiner aluminum untuk outdoor',
            'image' => null,
        ]);

        // ==================== PENGIRIMAN LOKAL BANTUL ====================
        Shipping::create([
            'name' => 'Pengiriman Lokal 0-5km',
            'cost' => 5000,
            'estimated_days' => '30-60 menit',
            'description' => 'Pengiriman lokal Bantul jarak 0-5km, diantar langsung oleh kurir kami'
        ]);

        Shipping::create([
            'name' => 'Pengiriman Lokal 5-10km',
            'cost' => 10000,
            'estimated_days' => '1-2 jam',
            'description' => 'Pengiriman lokal Bantul jarak 5-10km, diantar langsung oleh kurir kami'
        ]);
    }
}
