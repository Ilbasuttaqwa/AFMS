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
        if (!Schema::hasTable('bonus_gaji')) {
            Schema::create('bonus_gaji', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_user');
                $table->decimal('jumlah_bonus', 15, 2);
                $table->string('keterangan')->nullable();
                $table->date('bulan_tahun');
                $table->timestamps();
                
                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_gaji');
    }
};