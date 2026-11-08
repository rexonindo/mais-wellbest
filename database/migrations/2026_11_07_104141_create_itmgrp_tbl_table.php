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
        Schema::create('itmgrp_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('itm_grp', 50);
            $table->string('itm_type', 50)->unique('itm_type_unique');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->unique(['itm_type', 'itm_grp'], 'itm_grp_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itmgrp_tbl');
    }
};
