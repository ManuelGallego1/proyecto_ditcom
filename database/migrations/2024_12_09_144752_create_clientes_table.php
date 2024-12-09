<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->string('cc')->unique();
            $table->string('p_nombre');
            $table->string('s_nombre')->nullable();
            $table->string('p_apellido');
            $table->string('s_apellido')->nullable();
            $table->string('email')->unique();
            $table->string('numero', 10);

            $table->primary('cc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
