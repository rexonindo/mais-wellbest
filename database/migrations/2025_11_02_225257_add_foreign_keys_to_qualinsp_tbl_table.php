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
        Schema::table('qualinsp_tbl', function (Blueprint $table) {
            $table->foreign(['wo_no'], 'qualinsp_tbl_ibfk_1')->references(['wo_no'])->on('wo_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['itm_cd'], 'qualinsp_tbl_ibfk_2')->references(['itm_cd'])->on('itm_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['pic_id'], 'qualinsp_tbl_ibfk_3')->references(['emp_id'])->on('empl_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualinsp_tbl', function (Blueprint $table) {
            $table->dropForeign('qualinsp_tbl_ibfk_1');
            $table->dropForeign('qualinsp_tbl_ibfk_2');
            $table->dropForeign('qualinsp_tbl_ibfk_3');
        });
    }
};
