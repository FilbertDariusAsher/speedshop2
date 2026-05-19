<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('harga_akhir', 'harga_negoziasi');
            $table->decimal('harga_jual', 12, 2)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('harga_negoziasi', 'harga_akhir');
            $table->dropColumn('harga_jual');
        });
    }
};