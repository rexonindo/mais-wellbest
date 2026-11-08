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
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id');
            $table->string('model_type');
            $table->bigInteger('model_id');

            $table->index(['model_id', 'model_type', 'role_id'], 'model_has_roles_model_id_model_type_index');
            $table->unique(['role_id', 'model_type', 'model_id'], 'unique_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_roles');
    }
};
