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
        Schema::create('archived_acquisition_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('apartment_id');
            $table->bigInteger('item_id');
            $table->string('product_name');
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_used', 10, 2);
            $table->timestamp('expiration_date');
            $table->timestamp('archive_date');
            $table->foreign('apartment_id')
                ->references('id')
                ->on('apartments')
                ->onDelete('cascade');
            $table->foreign('item_id')
                ->references('id')
                ->on('acquisition_items')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_acquisition_items');
    }
};
