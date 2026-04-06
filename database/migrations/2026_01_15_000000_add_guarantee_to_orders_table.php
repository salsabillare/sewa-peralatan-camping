<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jaminan identitas
            $table->enum('guarantee_type', ['ktp', 'sim', 'passport', 'kartu_pelajar', 'lainnya'])->nullable()->comment('Tipe jaminan identitas yang digunakan');
            $table->string('guarantee_number')->nullable()->comment('Nomor identitas untuk jaminan');
            $table->timestamp('guarantee_collected_at')->nullable()->comment('Waktu jaminan diambil oleh kurir');
            $table->timestamp('guarantee_returned_at')->nullable()->comment('Waktu jaminan dikembalikan');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['guarantee_type', 'guarantee_number', 'guarantee_collected_at', 'guarantee_returned_at']);
        });
    }
};
