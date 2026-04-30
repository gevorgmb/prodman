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
        Schema::create('stock_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('apartment_id');
            $table->bigInteger('item_id');
            $table->bigInteger('product_id')->unsigned();
            $table->string('product_name');
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_used', 10, 2);
            $table->timestamp('expiration_date');
            $table->foreign('apartment_id')
                ->references('id')
                ->on('apartments')
                ->onDelete('cascade');
            $table->foreign('item_id')
                ->references('id')
                ->on('acquisition_items')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_products');
    }
};
