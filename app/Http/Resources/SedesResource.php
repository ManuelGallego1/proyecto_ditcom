<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SedesResource extends JsonResource
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
            'nombre' => $this->nombre,
            'coordinador_id' => $this->coordinador_id,
            'coordinador' => $this->coordinador->name,
            'activo' => $this->activo == 1 ? 'si' : 'no',
        ];
    }
}
