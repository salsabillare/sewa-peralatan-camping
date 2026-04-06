<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Modifikasi kolom subtotal untuk menambahkan default value
            $table->decimal('subtotal', 10, 2)->default(0)->change();
        });
    }

    /**
     * Rollback the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Kembalikan ke kondisi sebelumnya
            $table->decimal('subtotal', 10, 2)->change();
        });
    }
};
