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
        Schema::create('ivmovwo_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wo_no', 50);
            $table->string('itm_cd', 50)->index('itm_cd_key');
            $table->string('proc_cd', 50)->index('proc_cd_key');
            $table->string('wip_cd', 50);
            $table->dateTime('trans_dt');
            $table->enum('mov_type', ['IN', 'OUT', 'SCRAP', 'REWORK_IN', 'REWORK_OUT', 'SO']);
            $table->float('qty');
            $table->string('ref_table', 50)->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ivmovwo_tbl');
    }
};
