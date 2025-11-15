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
        Schema::create('prdroute_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('itm_type', 50);
            $table->integer('seq_no');
            $table->string('proc_cd', 50)->index('proc_cd');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->unique(['itm_type', 'seq_no', 'proc_cd'], 'itm_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prdroute_tbl');
    }
};
