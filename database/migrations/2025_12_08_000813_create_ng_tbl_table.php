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
        Schema::create('ng_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ng_nm', 50)->index('ng_nm');
            $table->string('dsc', 200)->nullable();
            $table->string('location', 50)->nullable();
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
        Schema::dropIfExists('ng_tbl');
    }
};
