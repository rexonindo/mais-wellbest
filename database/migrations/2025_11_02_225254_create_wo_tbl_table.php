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
        Schema::create('wo_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wo_no', 50)->unique('wo_no');
            $table->string('itm_cd', 50)->index('itm_cd');
            $table->string('po_no', 50)->nullable();
            $table->date('req_dt')->nullable();
            $table->float('plan_qty')->nullable();
            $table->float('plan_qty_pnl')->nullable();
            $table->date('start_dt')->nullable();
            $table->date('end_dt')->nullable();
            $table->enum('stats', ['Planned', 'In Progress', 'Completed', 'Cancelled'])->nullable()->default('Planned');
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
        Schema::dropIfExists('wo_tbl');
    }
};
