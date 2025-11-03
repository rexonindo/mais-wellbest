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
        Schema::create('mchn_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mchn_cd', 50)->unique('mchn_cd');
            $table->string('mchn_nm', 100)->nullable();
            $table->string('dept_cd', 20)->nullable()->index('dept_cd');
            $table->string('uom', 20)->nullable();
            $table->string('dsc', 50)->nullable();
            $table->enum('stats', ['Running', 'Idle', 'Maintenance', 'Breakdown'])->nullable()->default('Idle');
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
        Schema::dropIfExists('mchn_tbl');
    }
};
