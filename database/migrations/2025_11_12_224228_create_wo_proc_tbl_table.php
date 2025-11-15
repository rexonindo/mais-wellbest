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
        Schema::create('wo_proc_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wo_no', 50)->index('wo_no');
            $table->integer('seq_no');
            $table->string('proc_cd', 50);
            $table->integer('cav');
            $table->integer('shoot_qty');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->unique(['wo_no', 'seq_no'], 'ukey1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo_proc_tbl');
    }
};
