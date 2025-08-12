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
        Schema::create('bon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->decimal('jumlah_bon', 15, 2);
            $table->decimal('sisa_bon', 15, 2);
            $table->decimal('cicilan_per_bulan', 15, 2);
            $table->date('tanggal_bon');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'lunas', 'dibatalkan'])->default('aktif');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon');
    }
};