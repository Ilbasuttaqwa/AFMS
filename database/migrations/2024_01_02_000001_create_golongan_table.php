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
        Schema::create('golongan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_golongan');
            $table->decimal('gaji_pokok', 15, 2);
            $table->time('jam_masuk_pagi')->default('08:00:00');
            $table->time('jam_pulang_siang')->default('12:00:00');
            $table->time('jam_masuk_siang')->default('13:00:00');
            $table->time('jam_pulang_sore')->default('17:00:00');
            $table->integer('maksimal_ceklok_pagi')->default(30); // dalam menit
            $table->integer('maksimal_ceklok_siang')->default(30); // dalam menit
            $table->decimal('denda_keterlambatan', 10, 2)->default(0);
            $table->decimal('potongan_tidak_masuk', 10, 2)->default(0);
            $table->integer('jatah_libur')->default(12); // per tahun
            $table->decimal('bonus_tidak_libur', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golongan');
    }
};