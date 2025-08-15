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
        if (!Schema::hasTable('jadwal_kerja')) {
            Schema::create('jadwal_kerja', function (Blueprint $table) {
                $table->id();
                $table->time('jam_masuk')->default('08:00:00');
                $table->time('jam_pulang')->default('17:00:00');
                $table->integer('toleransi_keterlambatan')->default(15);
                $table->integer('potongan_per_menit')->default(1000);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};
