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
        Schema::table('proc_tbl', function (Blueprint $table) {
            $table->foreign(['dept_cd'], 'proc_tbl_ibfk_1')->references(['dept_cd'])->on('dept_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proc_tbl', function (Blueprint $table) {
            $table->dropForeign('proc_tbl_ibfk_1');
        });
    }
};
