<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SedeVendedor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'sede_vendedor';

    protected $fillable = [
        'vendedor_id',
        'sede_id',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function sede()
    {
        return $this->belongsTo(Sedes::class, 'sede_id');
    }
}