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
            // Pengaturan keterlambatan berdasarkan rentang menit
            if (!Schema::hasColumn('jabatans', 'toleransi_keterlambatan')) {
                $table->integer('toleransi_keterlambatan')->default(15)->after('potongan_keterlambatan')->comment('Toleransi keterlambatan dalam menit');
            }
            
            // Rentang 1: 0-15 menit (biasanya gratis/toleransi)
            if (!Schema::hasColumn('jabatans', 'rentang1_dari')) {
                $table->integer('rentang1_dari')->default(0)->after('toleransi_keterlambatan');
            }
            if (!Schema::hasColumn('jabatans', 'rentang1_sampai')) {
                $table->integer('rentang1_sampai')->default(15)->after('rentang1_dari');
            }
            if (!Schema::hasColumn('jabatans', 'rentang1_denda')) {
                $table->decimal('rentang1_denda', 15, 2)->default(0)->after('rentang1_sampai');
            }
            
            // Rentang 2: 16-30 menit
            if (!Schema::hasColumn('jabatans', 'rentang2_dari')) {
                $table->integer('rentang2_dari')->default(16)->after('rentang1_denda');
            }
            if (!Schema::hasColumn('jabatans', 'rentang2_sampai')) {
                $table->integer('rentang2_sampai')->default(30)->after('rentang2_dari');
            }
            if (!Schema::hasColumn('jabatans', 'rentang2_denda')) {
                $table->decimal('rentang2_denda', 15, 2)->default(5000)->after('rentang2_sampai');
            }
            
            // Rentang 3: 31-45 menit (sesuai permintaan Anda)
            if (!Schema::hasColumn('jabatans', 'rentang3_dari')) {
                $table->integer('rentang3_dari')->default(31)->after('rentang2_denda');
            }
            if (!Schema::hasColumn('jabatans', 'rentang3_sampai')) {
                $table->integer('rentang3_sampai')->default(45)->after('rentang3_dari');
            }
            if (!Schema::hasColumn('jabatans', 'rentang3_denda')) {
                $table->decimal('rentang3_denda', 15, 2)->default(10000)->after('rentang3_sampai');
            }
            
            // Rentang 4: 46-60 menit
            if (!Schema::hasColumn('jabatans', 'rentang4_dari')) {
                $table->integer('rentang4_dari')->default(46)->after('rentang3_denda');
            }
            if (!Schema::hasColumn('jabatans', 'rentang4_sampai')) {
                $table->integer('rentang4_sampai')->default(60)->after('rentang4_dari');
            }
            if (!Schema::hasColumn('jabatans', 'rentang4_denda')) {
                $table->decimal('rentang4_denda', 15, 2)->default(15000)->after('rentang4_sampai');
            }
            
            // Rentang 5: >60 menit
            if (!Schema::hasColumn('jabatans', 'rentang5_dari')) {
                $table->integer('rentang5_dari')->default(61)->after('rentang4_denda');
            }
            if (!Schema::hasColumn('jabatans', 'rentang5_sampai')) {
                $table->integer('rentang5_sampai')->default(999)->after('rentang5_dari')->comment('999 = unlimited');
            }
            if (!Schema::hasColumn('jabatans', 'rentang5_denda')) {
                $table->decimal('rentang5_denda', 15, 2)->default(25000)->after('rentang5_sampai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn([
                'toleransi_keterlambatan',
                'rentang1_dari', 'rentang1_sampai', 'rentang1_denda',
                'rentang2_dari', 'rentang2_sampai', 'rentang2_denda',
                'rentang3_dari', 'rentang3_sampai', 'rentang3_denda',
                'rentang4_dari', 'rentang4_sampai', 'rentang4_denda',
                'rentang5_dari', 'rentang5_sampai', 'rentang5_denda'
            ]);
        });
    }
};