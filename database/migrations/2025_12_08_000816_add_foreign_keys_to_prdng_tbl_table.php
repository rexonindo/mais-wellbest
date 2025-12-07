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
        Schema::table('prdng_tbl', function (Blueprint $table) {
            $table->foreign(['id_prd'], 'prdng_tbl_ibfk_1')->references(['id'])->on('prdlog_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prdng_tbl', function (Blueprint $table) {
            $table->dropForeign('prdng_tbl_ibfk_1');
        });
    }
};
