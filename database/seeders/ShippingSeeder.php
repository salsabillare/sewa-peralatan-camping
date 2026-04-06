<?php

namespace Database\Seeders;

use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        Shipping::truncate();

        Shipping::create([
            'name' => 'Standar Lokal Bantul',
            'cost' => 15000,
            'estimated_days' => '1-2',
            'description' => 'Pengiriman untuk area Bantul dan sekitarnya (max 10km)',
            'min_distance' => 0,
            'max_distance' => 10,
        ]);

        Shipping::create([
            'name' => 'Express Lokal Bantul',
            'cost' => 25000,
            'estimated_days' => '1 (Sama Hari)',
            'description' => 'Pengiriman express untuk area Bantul (max 10km)',
            'min_distance' => 0,
            'max_distance' => 10,
        ]);
    }
}
