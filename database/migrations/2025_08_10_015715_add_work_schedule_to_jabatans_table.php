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
        Schema::table('jabatans', function (Blueprint $table) {
            // Add work schedule columns if they don't exist
            if (!Schema::hasColumn('jabatans', 'jam_masuk')) {
                $table->time('jam_masuk')->nullable()->after('nama_jabatan');
            }
            if (!Schema::hasColumn('jabatans', 'jam_masuk_siang')) {
                $table->time('jam_masuk_siang')->nullable()->after('jam_masuk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk', 'jam_masuk_siang']);
        });
    }
};
