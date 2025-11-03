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
        Schema::create('proc_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('proc_cd', 50)->unique('proc_cd');
            $table->string('proc_nm', 100);
            $table->string('dept_cd', 20)->nullable()->index('dept_cd');
            $table->float('std_time')->nullable();
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
        Schema::dropIfExists('proc_tbl');
    }
};
