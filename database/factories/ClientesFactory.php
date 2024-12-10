<?php

namespace Database\Factories;

use App\Models\Clientes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clientes>
 */
class ClientesFactory extends Factory
{
    protected $model = Clientes::class;

    public function definition(): array
    {
        return [
            'cc' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'p_nombre' => $this->faker->firstName(),
            's_nombre' => $this->faker->firstName(),
            'p_apellido' => $this->faker->lastName(),
            's_apellido' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'numero' => $this->faker->phoneNumber(),
        ];
    }
}
