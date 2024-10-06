<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default super admin user
        DB::table('super_admins')->insert([
            'name' => 'New Default Admin',
            'email' => 'superadmin',
            'password' => Hash::make('portalAdmin666'), // Make sure to hash the password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, remove the user if you want to reverse this migration
        DB::table('super_admins')->where('email', 'newadmin@example.com')->delete();
    }
};
