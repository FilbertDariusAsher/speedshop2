<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('harga_beli', 12, 2)->nullable()->after('stock_amount');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('harga_final', 12, 2)->nullable()->after('harga_jual');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('harga_beli');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('harga_final');
        });
    }
};