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
        Schema::create('cust_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cust_cd', 20)->unique('cust_cd');
            $table->string('cust_nm', 100);
            $table->text('address')->nullable();
            $table->string('telp', 50)->nullable();
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
        Schema::dropIfExists('cust_tbl');
    }
};
