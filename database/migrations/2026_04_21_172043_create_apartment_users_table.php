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
        Schema::create('apartment_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('apartment_id');
            $table->bigInteger('user_id');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->foreign('apartment_id')->references('id')->on('apartments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_users');
    }
};
