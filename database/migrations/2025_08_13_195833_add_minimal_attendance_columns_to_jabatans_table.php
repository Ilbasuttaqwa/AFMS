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
            $table->time('minimal_absen_pagi')->nullable()->default('07:00:00')->comment('Minimal waktu absen pagi, absen sebelum jam ini akan dikenakan potongan');
            $table->time('minimal_absen_siang')->nullable()->default('12:00:00')->comment('Minimal waktu absen siang, absen sebelum jam ini akan dikenakan potongan');
            $table->decimal('potongan_absen_awal', 10, 2)->nullable()->default(10000)->comment('Potongan gaji per kejadian absen sebelum waktu minimal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn(['minimal_absen_pagi', 'minimal_absen_siang', 'potongan_absen_awal']);
        });
    }
};
