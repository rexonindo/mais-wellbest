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
        Schema::create('itm_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('itm_cd', 50)->unique('itm_cd');
            $table->string('itm_nm', 100);
            $table->string('itm_type', 50)->nullable();
            $table->boolean('fg_flg')->nullable();
            $table->string('uom', 20)->nullable();
            $table->float('std_rate')->nullable();
            $table->integer('cavity')->nullable();
            $table->string('cust_cd', 20)->nullable()->index('cust_cd_idx');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itm_tbl');
    }
};
