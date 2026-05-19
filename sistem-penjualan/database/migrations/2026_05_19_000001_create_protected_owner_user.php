<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PROTECTED_OWNER_EMAIL = 'lenawatisintya@gmail.com';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            $exists = DB::table('users')->where('email', self::PROTECTED_OWNER_EMAIL)->exists();

            if (!$exists) {
                DB::table('users')->insert([
                    'name' => 'Lenawati Sintya',
                    'email' => self::PROTECTED_OWNER_EMAIL,
                    'password' => Hash::make('lenawatisintya'),
                    'role' => 'owner',
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            DB::table('users')->where('email', self::PROTECTED_OWNER_EMAIL)->delete();
        }
    }
};
