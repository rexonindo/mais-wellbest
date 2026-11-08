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
        Schema::create('prdng_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_prd')->index('id_prd_key');
            $table->string('ng_nm', 50)->index('ng_nm_key');
            $table->float('ng_qty')->nullable();
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->index(['id_prd', 'ng_nm'], 'prd_ng_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prdng_tbl');
    }
};
