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
        Schema::table('itm_tbl', function (Blueprint $table) {
            $table->foreign(['cust_cd'], 'cust_cd')->references(['cust_cd'])->on('cust_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itm_tbl', function (Blueprint $table) {
            $table->dropForeign('cust_cd');
        });
    }
};
