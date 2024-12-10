<?php

namespace Database\Factories;

use App\Models\Celulares;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Celulares>
 */
class CelularesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Celulares::class;
    public function definition(): array
    {
        return [
            'marca' => $this->faker->company,      
            'modelo' => $this->faker->word,          
            'activo' => $this->faker->boolean, 
        ];
    }
}
