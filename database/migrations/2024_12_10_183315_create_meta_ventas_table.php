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
        Schema::create('meta_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_venta'); // 'movil' o 'fijo'
            $table->integer('cantidad'); // Meta de cantidad de ventas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_ventas');
    }
};
