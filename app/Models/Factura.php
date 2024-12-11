<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'referencia',
        'tipo_venta',
        'monto',
        'estado',
    ];
}
