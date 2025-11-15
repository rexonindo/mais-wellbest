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
        Schema::create('toolcav_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('itm_cd', 50)->index('itm_cd');
            $table->string('tool_cd', 50);
            $table->string('proc_cd', 50)->index('proc_cd');
            $table->integer('cav');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();

            $table->unique(['itm_cd', 'tool_cd', 'proc_cd'], 'itm_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toolcav_tbl');
    }
};
