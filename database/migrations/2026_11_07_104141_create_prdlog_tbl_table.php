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
        Schema::create('prdlog_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wo_no', 50)->index('wo_no_key');
            $table->string('itm_cd', 50)->index('itm_cd_key');
            $table->string('proc_cd', 50)->index('proc_cd_key');
            $table->integer('seq_no')->nullable();
            $table->string('mchn_cd', 50)->nullable();
            $table->string('emp_id', 20)->index('emp_id_key');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('cav')->nullable();
            $table->float('avail_qty')->nullable();
            $table->float('in_qty')->nullable();
            $table->float('out_qty')->nullable();
            $table->float('ng_qty')->nullable();
            $table->float('ng_qty_pcs')->nullable();
            $table->float('rwk_qty')->nullable();
            $table->float('rwk_qty_pcs')->nullable();
            $table->text('rmks')->nullable();
            $table->text('rmks_rwk')->nullable();
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
        Schema::dropIfExists('prdlog_tbl');
    }
};
