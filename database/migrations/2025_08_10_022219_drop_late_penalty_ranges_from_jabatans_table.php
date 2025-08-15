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
            // Drop late penalty range columns
            if (Schema::hasColumn('jabatans', 'rentang1_dari')) {
                $table->dropColumn('rentang1_dari');
            }
            if (Schema::hasColumn('jabatans', 'rentang1_sampai')) {
                $table->dropColumn('rentang1_sampai');
            }
            if (Schema::hasColumn('jabatans', 'rentang1_denda')) {
                $table->dropColumn('rentang1_denda');
            }
            if (Schema::hasColumn('jabatans', 'rentang2_dari')) {
                $table->dropColumn('rentang2_dari');
            }
            if (Schema::hasColumn('jabatans', 'rentang2_sampai')) {
                $table->dropColumn('rentang2_sampai');
            }
            if (Schema::hasColumn('jabatans', 'rentang2_denda')) {
                $table->dropColumn('rentang2_denda');
            }
            if (Schema::hasColumn('jabatans', 'rentang3_dari')) {
                $table->dropColumn('rentang3_dari');
            }
            if (Schema::hasColumn('jabatans', 'rentang3_sampai')) {
                $table->dropColumn('rentang3_sampai');
            }
            if (Schema::hasColumn('jabatans', 'rentang3_denda')) {
                $table->dropColumn('rentang3_denda');
            }
            if (Schema::hasColumn('jabatans', 'rentang4_dari')) {
                $table->dropColumn('rentang4_dari');
            }
            if (Schema::hasColumn('jabatans', 'rentang4_sampai')) {
                $table->dropColumn('rentang4_sampai');
            }
            if (Schema::hasColumn('jabatans', 'rentang4_denda')) {
                $table->dropColumn('rentang4_denda');
            }
            if (Schema::hasColumn('jabatans', 'rentang5_dari')) {
                $table->dropColumn('rentang5_dari');
            }
            if (Schema::hasColumn('jabatans', 'rentang5_sampai')) {
                $table->dropColumn('rentang5_sampai');
            }
            if (Schema::hasColumn('jabatans', 'rentang5_denda')) {
                $table->dropColumn('rentang5_denda');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            // Re-add late penalty range columns if needed
            $table->integer('rentang1_dari')->default(0)->after('toleransi_keterlambatan');
            $table->integer('rentang1_sampai')->default(15)->after('rentang1_dari');
            $table->decimal('rentang1_denda', 10, 2)->default(5000)->after('rentang1_sampai');
            
            $table->integer('rentang2_dari')->default(16)->after('rentang1_denda');
            $table->integer('rentang2_sampai')->default(30)->after('rentang2_dari');
            $table->decimal('rentang2_denda', 10, 2)->default(10000)->after('rentang2_sampai');
            
            $table->integer('rentang3_dari')->default(31)->after('rentang2_denda');
            $table->integer('rentang3_sampai')->default(45)->after('rentang3_dari');
            $table->decimal('rentang3_denda', 10, 2)->default(15000)->after('rentang3_sampai');
            
            $table->integer('rentang4_dari')->default(46)->after('rentang3_denda');
            $table->integer('rentang4_sampai')->default(60)->after('rentang4_dari');
            $table->decimal('rentang4_denda', 10, 2)->default(20000)->after('rentang4_sampai');
            
            $table->integer('rentang5_dari')->default(61)->after('rentang4_denda');
            $table->integer('rentang5_sampai')->default(999)->after('rentang5_dari');
            $table->decimal('rentang5_denda', 10, 2)->default(25000)->after('rentang5_sampai');
        });
    }
};
