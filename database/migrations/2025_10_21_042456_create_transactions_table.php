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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // ID transaksi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // kasir yang input
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // produk
            $table->integer('quantity'); // jumlah barang
            $table->integer('total'); // total harga
            $table->bigInteger('payment')->default(0); // uang bayar
            $table->bigInteger('change')->default(0);  // kembalian
            $table->enum('status', ['paid', 'pending', 'cancel'])->default('pending'); // status transaksi
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
