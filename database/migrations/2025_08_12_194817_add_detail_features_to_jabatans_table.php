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
            // Lateness penalty ranges
            $table->decimal('potongan_0_30', 10, 2)->default(0)->after('potongan_keterlambatan');
            $table->decimal('potongan_31_45', 10, 2)->default(10000)->after('potongan_0_30');
            $table->decimal('potongan_46_60', 10, 2)->default(15000)->after('potongan_31_45');
            $table->decimal('potongan_61_100', 10, 2)->default(25000)->after('potongan_46_60');
            $table->decimal('potongan_101_200', 10, 2)->default(50000)->after('potongan_61_100');
            $table->decimal('potongan_200_plus', 10, 2)->default(100000)->after('potongan_101_200');
            
            // Leave settings
            $table->integer('jatah_libur_per_bulan')->default(2)->after('jam_masuk_siang');
            $table->decimal('denda_per_hari_libur', 10, 2)->default(50000)->after('jatah_libur_per_bulan');
            $table->decimal('bonus_tidak_libur', 10, 2)->default(25000)->after('denda_per_hari_libur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            // Drop lateness penalty ranges
            $table->dropColumn([
                'potongan_0_30', 'potongan_31_45', 'potongan_46_60', 
                'potongan_61_100', 'potongan_101_200', 'potongan_200_plus'
            ]);
            
            // Drop leave settings
            $table->dropColumn([
                'jatah_libur_per_bulan', 'denda_per_hari_libur', 'bonus_tidak_libur'
            ]);
        });
    }
};
