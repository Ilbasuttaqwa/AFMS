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
        Schema::table('absensis', function (Blueprint $table) {
            $table->time('jam_istirahat')->nullable()->after('jam_masuk');
            $table->time('jam_masuk_sore')->nullable()->after('jam_istirahat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('jam_istirahat');
            $table->dropColumn('jam_masuk_sore');
        });
    }
};
