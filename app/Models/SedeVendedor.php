<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SedeVendedor extends Model
{
    public $timestamps = false;

    protected $table = 'sedevendedor';

    protected $fillable = [
        'vendedor_id',
        'sede_id',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /**
     * Get the movil records associated with the Sede.
     */
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
}
