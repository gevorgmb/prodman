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
        Schema::create('acquisition_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('acquisition_id');
            $table->bigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->foreign('acquisition_id')
                ->references('id')
                ->on('acquisitions')
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
        Schema::dropIfExists('acquisition_items');
    }
};
