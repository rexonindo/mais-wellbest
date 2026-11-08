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
        Schema::create('rwk_tbl_tmp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rwk_nm', 50)->unique('rwk_nm_key');
            $table->string('dsc', 200)->nullable();
            $table->string('location', 50)->nullable();
            $table->string('category', 50)->nullable();
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
        Schema::dropIfExists('rwk_tbl_tmp');
    }
};
