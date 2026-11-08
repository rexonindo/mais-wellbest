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
        Schema::table('toolcav_tbl', function (Blueprint $table) {
            $table->foreign(['itm_cd'], 'toolcav_tbl_ibfk_1')->references(['itm_cd'])->on('itm_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['proc_cd'], 'toolcav_tbl_ibfk_2')->references(['proc_cd'])->on('proc_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toolcav_tbl', function (Blueprint $table) {
            $table->dropForeign('toolcav_tbl_ibfk_1');
            $table->dropForeign('toolcav_tbl_ibfk_2');
        });
    }
};
