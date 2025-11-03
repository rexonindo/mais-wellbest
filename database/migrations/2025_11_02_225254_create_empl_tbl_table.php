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
        Schema::create('empl_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('emp_id', 20)->unique('emp_id');
            $table->string('emp_nm', 100);
            $table->string('email')->nullable()->unique('email');
            $table->string('psition', 100)->nullable();
            $table->string('dept_cd', 20)->nullable()->index('dept_cd');
            $table->string('shift_cd', 20)->nullable()->index('shift_cd');
            $table->enum('stats', ['Active', 'Inactive'])->nullable()->default('Active');
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
        Schema::dropIfExists('empl_tbl');
    }
};
