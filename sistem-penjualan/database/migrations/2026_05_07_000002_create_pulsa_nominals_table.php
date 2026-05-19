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
        Schema::create('pulsa_nominals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('pulsa_providers')->onDelete('cascade');
            $table->integer('nominal_amount'); // 10, 20, 50, 100 (dalam ribu: 10 = 10k)
            $table->integer('markup')->default(0); // Markup fixed amount (bukan persen)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pulsa_nominals');
    }
};
