<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SedeVendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendedor_nombre' => $this->vendedor->name ?? 'Vendedor no asignado',
            'sede_nombre' => $this->sede->nombre ?? 'Sede no asignada',
        ];
    }
}
