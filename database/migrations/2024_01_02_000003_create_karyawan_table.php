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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->string('nip')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_masuk');
            $table->foreignId('golongan_id')->constrained('golongan')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->string('fingerprint_id')->unique()->nullable(); // ID untuk SDK fingerprint
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};