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
        Schema::create('token_nominals', function (Blueprint $table) {
            $table->id();
            $table->integer('nominal_amount'); // 300000, 500000, 1000000 (dalam rupiah)
            $table->integer('harga_final'); // Harga yang akan dijual ke customer
            $table->integer('profit')->default(0); // Keuntungan fixed amount (bukan persen)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_nominals');
    }
};
