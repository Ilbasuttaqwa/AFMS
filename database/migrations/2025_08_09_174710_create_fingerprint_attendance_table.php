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
        Schema::create('fingerprint_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('device_user_id'); // ID user di device fingerprint
            $table->string('device_ip'); // IP address device
            $table->datetime('attendance_time'); // Waktu absensi dari device
            $table->tinyInteger('attendance_type')->default(1); // 1=masuk, 2=keluar, 3=istirahat_keluar, 4=istirahat_masuk
            $table->string('verification_type')->nullable(); // Tipe verifikasi (fingerprint, password, card, etc)
            $table->boolean('is_processed')->default(false); // Apakah sudah diproses ke tabel absensi utama
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key ke users table (setelah mapping)
            $table->unsignedBigInteger('cabang_id')->nullable(); // Foreign key ke cabang table
            $table->text('raw_data')->nullable(); // Data mentah dari device untuk debugging
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index(['device_user_id', 'device_ip']);
            $table->index(['attendance_time']);
            $table->index(['is_processed']);
            $table->index(['user_id']);
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprint_attendance');
    }
};
