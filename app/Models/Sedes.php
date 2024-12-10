<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sedes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'coordinador_id',
        'activo',
    ];

    public function coordinador()
    {
        return $this->belongsTo(User::class, 'coordinador_id');
    }

    /**
     * Get the movil records associated with the Sede.
     */
    public function moviles()
    {
        return $this->hasMany(Movil::class, 'sede_id');
    }
}
