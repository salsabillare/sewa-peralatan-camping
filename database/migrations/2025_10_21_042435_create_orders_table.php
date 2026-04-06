<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi user (pelanggan)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Kasir yang memproses
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();

            // Detail order
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['pending','paid','shipped','completed','cancelled'])->default('pending');
            $table->enum('payment_method', ['transfer','cod','ewallet'])->default('transfer');
            $table->text('address');

            // Waktu diproses kasir
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
