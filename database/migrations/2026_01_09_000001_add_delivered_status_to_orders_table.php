<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Untuk MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'diproses', 'siap_dikirim', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
        }
        // Untuk SQLite
        else {
            // SQLite tidak support ENUM, tapi tetap bisa dihandle di aplikasi
            // Kolom tetap VARCHAR tapi kita handle enum di model
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'diproses', 'siap_dikirim', 'shipped', 'cancelled') DEFAULT 'pending'");
        }
    }
};
