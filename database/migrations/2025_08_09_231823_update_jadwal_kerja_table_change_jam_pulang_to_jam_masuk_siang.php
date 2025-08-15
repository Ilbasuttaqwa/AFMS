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
        Schema::table('jadwal_kerja', function (Blueprint $table) {
            // Rename jam_pulang to jam_masuk_siang
            $table->renameColumn('jam_pulang', 'jam_masuk_siang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_kerja', function (Blueprint $table) {
            // Rename back jam_masuk_siang to jam_pulang
            $table->renameColumn('jam_masuk_siang', 'jam_pulang');
        });
    }
};
