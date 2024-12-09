<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celulares extends Model
{
    //
    use HasFactory;

    public $timestamps = false;

    protected $table = 'celulares';

    protected $fillable = [
        'marca',
        'modelo',
        'activo',
    ];

    public function moviles()
    {
        return $this->hasMany(Movil::class, 'celulares_id');
    }
}
