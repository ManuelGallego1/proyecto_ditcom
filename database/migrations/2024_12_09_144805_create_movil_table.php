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
        Schema::create('movil', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('min', 10);
            $table->string('imei', 15);
            $table->string('iccid', 17);
            $table->enum('tipo', [
                'kit prepago', 'kit financiado', 'wb', 'up grade',
                'linea nueva', 'reposicion', 'portabilidad pre',
                'portabilidad pos', 'venta de tecnologia', 'equipo pos',
            ]);
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('celulares_id');
            $table->string('cliente_cc');
            $table->string('factura');
            $table->string('ingreso_caja');
            $table->double('valor_total');
            $table->double('valor_recarga')->nullable();
            $table->enum('tipo_producto', ['residencial', 'pyme']);
            $table->unsignedBigInteger('vendedor_id');
            $table->unsignedBigInteger('sede_id');
            $table->enum('financiera', ['crediminuto', 'celya', 'brilla', 'N/A']);
            $table->unsignedBigInteger('coordinador_id');
            $table->enum('estado', ['pendiente', 'exitosa', 'rechazada', 'cancelada', 'terminada']);

            $table->foreign('plan_id')->references('id')->on('planes')->onDelete('cascade');
            $table->foreign('celulares_id')->references('id')->on('celulares')->onDelete('cascade');
            $table->foreign('coordinador_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('movil');
    }
};
