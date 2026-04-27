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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('apartment_id');
            $table->string('name');
            $table->smallInteger('importance')->default(0);
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->text('description')->nullable();
            $table->unique(['apartment_id', 'name']);
            $table->foreign('apartment_id')
                ->references('id')
                ->on('apartments')
                ->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
