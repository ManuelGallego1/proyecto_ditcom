<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'clientes';

    protected $fillable = [
        'cc',
        'p_nombre',
        's_nombre',
        'p_apellido',
        's_apellido',
        'email',
        'numero',
    ];

    /**
     * Get the fijo records associated with the client.
     */
    public function fijos()
    {
        return $this->hasMany(Fijo::class, 'cliente_cc', 'cc');
    }

    /**
     * Get the movil records associated with the client.
     */
    public function moviles()
    {
        return $this->hasMany(Movil::class, 'cliente_cc', 'cc');
    }
}
