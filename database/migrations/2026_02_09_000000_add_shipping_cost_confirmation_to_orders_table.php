<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Field untuk admin menginput biaya ongkir
            $table->decimal('admin_shipping_cost', 10, 2)->nullable()->comment('Biaya ongkir yang ditetapkan admin');
            $table->boolean('shipping_cost_confirmed')->default(false)->comment('Flag apakah biaya ongkir sudah dikonfirmasi admin');
            $table->timestamp('shipping_cost_confirmed_at')->nullable()->comment('Waktu admin mengkonfirmasi biaya ongkir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['admin_shipping_cost', 'shipping_cost_confirmed', 'shipping_cost_confirmed_at']);
        });
    }
};
