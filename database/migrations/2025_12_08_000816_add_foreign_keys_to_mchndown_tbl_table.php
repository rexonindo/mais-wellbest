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
        Schema::table('mchndown_tbl', function (Blueprint $table) {
            $table->foreign(['mchn_cd'], 'mchndown_tbl_ibfk_1')->references(['mchn_cd'])->on('mchn_tbl')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mchndown_tbl', function (Blueprint $table) {
            $table->dropForeign('mchndown_tbl_ibfk_1');
        });
    }
};
