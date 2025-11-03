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
        Schema::table('prdlog_tbl', function (Blueprint $table) {
            $table->foreign(['wo_no'], 'prdlog_tbl_ibfk_1')->references(['wo_no'])->on('wo_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['itm_cd'], 'prdlog_tbl_ibfk_2')->references(['itm_cd'])->on('itm_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['proc_cd'], 'prdlog_tbl_ibfk_3')->references(['proc_cd'])->on('proc_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mchn_cd'], 'prdlog_tbl_ibfk_4')->references(['mchn_cd'])->on('mchn_tbl')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['emp_id'], 'prdlog_tbl_ibfk_5')->references(['emp_id'])->on('empl_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prdlog_tbl', function (Blueprint $table) {
            $table->dropForeign('prdlog_tbl_ibfk_1');
            $table->dropForeign('prdlog_tbl_ibfk_2');
            $table->dropForeign('prdlog_tbl_ibfk_3');
            $table->dropForeign('prdlog_tbl_ibfk_4');
            $table->dropForeign('prdlog_tbl_ibfk_5');
        });
    }
};
