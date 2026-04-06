<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan 'diproses' ke ENUM
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','diproses','paid','shipped','completed','cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM awal
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending'");
    }
};
