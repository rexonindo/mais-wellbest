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
            $table->string('wo_no', 50)->index('wo_no');
            $table->string('itm_cd', 50)->index('itm_cd');
            $table->string('proc_cd', 50)->index('proc_cd');
            $table->string('mchn_cd', 50)->nullable()->index('mchn_cd');
            $table->string('emp_id', 20)->index('emp_id');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->float('avail_qty')->nullable();
            $table->float('in_qty')->nullable();
            $table->float('out_qty')->nullable();
            $table->float('ng_qty')->nullable()->default(0);
            $table->integer('rwk_qty')->nullable()->default(0);
            $table->text('rmks')->nullable();
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
