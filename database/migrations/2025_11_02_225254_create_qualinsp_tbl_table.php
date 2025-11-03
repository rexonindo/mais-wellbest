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
        Schema::create('qualinsp_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wo_no', 50)->index('wo_no');
            $table->string('itm_cd', 50)->index('itm_cd');
            $table->float('insp_qty')->nullable();
            $table->float('passed_qty')->nullable();
            $table->float('failed_qty')->nullable();
            $table->dateTime('insp_time')->nullable()->useCurrent();
            $table->string('pic_id', 20)->index('pic_id');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('qualinsp_tbl');
    }
};
