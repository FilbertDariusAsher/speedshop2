<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('harga_jual', 12, 2)->nullable()->after('price_per_unit');
            $table->renameColumn('harga_akhir', 'harga_negoziasi');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('harga_jual');
            $table->renameColumn('harga_negoziasi', 'harga_akhir');
        });
    }
};