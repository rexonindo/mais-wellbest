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
        Schema::table('prdroute_tbl', function (Blueprint $table) {
            $table->foreign(['proc_cd'], 'prdroute_tbl_ibfk_1')->references(['proc_cd'])->on('proc_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prdroute_tbl', function (Blueprint $table) {
            $table->dropForeign('prdroute_tbl_ibfk_1');
        });
    }
};
