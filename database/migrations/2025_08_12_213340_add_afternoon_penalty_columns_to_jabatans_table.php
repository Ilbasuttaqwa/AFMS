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
            $table->decimal('potongan_siang_0_30', 10, 2)->default(0)->after('potongan_200_plus');
            $table->decimal('potongan_siang_31_45', 10, 2)->default(0)->after('potongan_siang_0_30');
            $table->decimal('potongan_siang_46_60', 10, 2)->default(0)->after('potongan_siang_31_45');
            $table->decimal('potongan_siang_61_100', 10, 2)->default(0)->after('potongan_siang_46_60');
            $table->decimal('potongan_siang_101_200', 10, 2)->default(0)->after('potongan_siang_61_100');
            $table->decimal('potongan_siang_200_plus', 10, 2)->default(0)->after('potongan_siang_101_200');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn([
                'potongan_siang_0_30',
                'potongan_siang_31_45',
                'potongan_siang_46_60',
                'potongan_siang_61_100',
                'potongan_siang_101_200',
                'potongan_siang_200_plus'
            ]);
        });
    }
};
