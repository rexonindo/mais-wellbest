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
        Schema::table('wo_proc_tbl', function (Blueprint $table) {
            $table->foreign(['wo_no'], 'wo_proc_tbl_ibfk_1')->references(['wo_no'])->on('wo_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wo_proc_tbl', function (Blueprint $table) {
            $table->dropForeign('wo_proc_tbl_ibfk_1');
        });
    }
};
