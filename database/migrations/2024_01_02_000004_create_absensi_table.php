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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_masuk_pagi')->nullable();
            $table->time('waktu_keluar_istirahat')->nullable();
            $table->time('waktu_masuk_istirahat')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->boolean('terlambat_pagi')->default(false);
            $table->boolean('terlambat_siang')->default(false);
            $table->integer('menit_keterlambatan_pagi')->default(0);
            $table->integer('menit_keterlambatan_siang')->default(0);
            $table->enum('status', ['hadir', 'tidak_hadir', 'izin', 'sakit', 'cuti'])->default('tidak_hadir');
            $table->text('keterangan')->nullable();
            $table->string('fingerprint_device_id')->nullable(); // ID device fingerprint
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};