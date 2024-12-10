<?php

namespace Database\Factories;

use App\Models\Fijo;
use App\Models\User;
use App\Models\Sede;
use App\Models\Clientes;
use Illuminate\Database\Eloquent\Factories\Factory;

class FijoFactory extends Factory
{
    protected $model = Fijo::class;

    public function definition()
    {
        return [
            'fecha_instalacion' => $this->faker->date(),
            'fecha_legalizacion' => $this->faker->date(),
            'servicios_adicionales' => $this->faker->boolean(),
            'estrato' => $this->faker->numberBetween(1, 6),
            'cuenta' => $this->faker->word(),
            'OT' => $this->faker->word(),
            'tipo_producto' => $this->faker->word(),
            'total_servicios' => $this->faker->randomFloat(2, 10, 500),
            'total_adicionales' => $this->faker->randomFloat(2, 5, 200),
            'cliente_cc' => Clientes::factory(), // Relacionado con el cliente
            'sede_id' => Sede::factory(), // Relacionado con la sede
            'vendedor_id' => User::factory(), // Relacionado con el vendedor (user)
            'estado' => $this->faker->word(),
            'convergente' => $this->faker->boolean(),
            'ciudad' => $this->faker->city(),
        ];
    }
}
