<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'plan' => $this->codigo . ' - ' . $this->nombre,
            'stock' => $this->stock == 1 ? 'si' : 'no',
            'creado' => $this->created_at,
            'actualizado' => $this->updated_at,
        ];
    }
}
