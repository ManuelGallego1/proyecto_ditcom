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
        Schema::create('fijo', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('fecha_instalacion')->nullable();
            $table->date('fecha_legalizacion')->nullable();
            $table->string('servicios_adicionales');
            $table->enum('estrato', ['1', '2', '3', '4', '5', '6', 'NR']);
            $table->integer('cuenta');
            $table->integer('OT');
            $table->enum('tipo_producto', ['residencial', 'pyme']);
            $table->enum('total_servicios', ['1', '2', '3'])->nullable();
            $table->enum('total_adicionales', ['1', '2', '3'])->nullable();
            $table->string('cliente_cc');
            $table->unsignedBigInteger('sede_id');
            $table->unsignedBigInteger('vendedor_id');
            $table->enum('estado', ['digitado', 'reclamar', 'instalado', 'cancelado', 'razonado']);
            $table->string('convergente');
            $table->string('ciudad');

            $table->foreign('vendedor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sede_id')->references('id')->on('sedes')->onDelete('cascade');
            $table->foreign('cliente_cc')->references('cc')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fijo');
    }
};
