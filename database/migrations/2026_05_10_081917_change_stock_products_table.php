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
        Schema::table('stock_products', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('quantity_used');
            $table->decimal('quantity_available', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_products', function (Blueprint $table) {
            $table->dropColumn('quantity_available');
            $table->decimal('quantity')->default(0);
            $table->decimal('quantity_used')->default(0);
        });
    }
};
