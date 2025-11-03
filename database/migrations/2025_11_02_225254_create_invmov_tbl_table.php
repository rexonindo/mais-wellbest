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
        Schema::create('invmov_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('loc_cd', 50);
            $table->string('itm_cd', 50)->index('itm_cd');
            $table->enum('mov_type', ['IN', 'OUT'])->nullable();
            $table->float('qty')->nullable();
            $table->string('ref_type', 50)->nullable();
            $table->integer('ref_id')->nullable();
            $table->dateTime('mov_time')->nullable()->useCurrent();
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
        Schema::dropIfExists('invmov_tbl');
    }
};
