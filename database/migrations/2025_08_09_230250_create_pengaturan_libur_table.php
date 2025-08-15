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
        Schema::create('pengaturan_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_libur');
            $table->string('nama_libur');
            $table->text('keterangan')->nullable();
            $table->enum('jenis_libur', ['nasional', 'perusahaan', 'khusus'])->default('perusahaan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_libur');
    }
};
