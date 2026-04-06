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
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom lama yang tidak diperlukan
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            
            if (Schema::hasColumn('transactions', 'quantity')) {
                $table->dropColumn('quantity');
            }

            // Tambahkan kolom transaction_code jika belum ada
            if (!Schema::hasColumn('transactions', 'transaction_code')) {
                $table->string('transaction_code')->unique()->nullable();
            }
        });
    }

    /**
     * Rollback the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'product_id')) {
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
            }
            
            if (Schema::hasColumn('transactions', 'transaction_code')) {
                $table->dropColumn('transaction_code');
            }
        });
    }
};
