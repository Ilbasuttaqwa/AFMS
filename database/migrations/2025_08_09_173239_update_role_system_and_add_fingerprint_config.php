<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update users table - change is_admin to role system
        Schema::table('users', function (Blueprint $table) {
            // Add new role column
            $table->enum('role', ['manager', 'admin'])->default('admin')->after('is_admin');
        });

        // Update existing data: is_admin = 1 becomes 'manager', is_admin = 0 becomes 'admin'
        DB::statement("UPDATE users SET role = CASE WHEN is_admin = 1 THEN 'manager' ELSE 'admin' END");

        // Remove old is_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        // Add fingerprint configuration to cabang table
        Schema::table('cabang', function (Blueprint $table) {
            $table->string('fingerprint_ip')->nullable()->after('kode_cabang');
            $table->integer('fingerprint_port')->default(4370)->after('fingerprint_ip');
            $table->boolean('fingerprint_active')->default(false)->after('fingerprint_port');
            $table->timestamp('last_sync')->nullable()->after('fingerprint_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore is_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        // Restore data: 'manager' becomes is_admin = 1, 'admin' becomes is_admin = 0
        DB::statement("UPDATE users SET is_admin = CASE WHEN role = 'manager' THEN 1 ELSE 0 END");

        // Remove role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Remove fingerprint configuration from cabang table
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn(['fingerprint_ip', 'fingerprint_port', 'fingerprint_active', 'last_sync']);
        });
    }
};
