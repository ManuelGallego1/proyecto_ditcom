<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'documento identidad' => $this->cc,
            'nombre_completo' => "{$this->p_nombre} {$this->s_nombre} {$this->p_apellido} {$this->s_apellido}",
            'email' => $this->email,
            'numero' => $this->numero,
            'creado' => $this->created_at,
            'actualizado' => $this->updated_at,
        ];
    }
}
