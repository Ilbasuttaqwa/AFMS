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
            if (!Schema::hasColumn('jabatans', 'batas_keterlambatan')) {
                $table->integer('batas_keterlambatan')->default(30)->after('gaji_pokok');
            }
            if (!Schema::hasColumn('jabatans', 'potongan_keterlambatan')) {
                $table->decimal('potongan_keterlambatan', 15, 2)->default(0)->after('batas_keterlambatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn(['batas_keterlambatan', 'potongan_keterlambatan']);
        });
    }
};