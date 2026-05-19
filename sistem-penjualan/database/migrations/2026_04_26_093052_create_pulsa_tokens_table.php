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
        Schema::create('pulsa_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->enum('type', ['pulsa', 'paket_internet', 'token_listrik']);
            $table->string('provider')->nullable(); // untuk pulsa & paket internet
            $table->string('phone_number')->nullable(); // untuk pulsa & paket internet
            $table->string('token_number')->nullable(); // untuk token listrik
            $table->string('amount'); // bisa "10", "5GB+10GB", "50000"
            $table->decimal('price', 15, 0);
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pulsa_tokens');
    }
};
