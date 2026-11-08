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
        Schema::table('prdrwk_tbl', function (Blueprint $table) {
            $table->foreign(['id_prd'], 'prdrwk_tbl_ibfk_1')->references(['id'])->on('prdlog_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['rwk_nm'], 'prdrwk_tbl_ibfk_2')->references(['rwk_nm'])->on('rwk_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prdrwk_tbl', function (Blueprint $table) {
            $table->dropForeign('prdrwk_tbl_ibfk_1');
            $table->dropForeign('prdrwk_tbl_ibfk_2');
        });
    }
};
