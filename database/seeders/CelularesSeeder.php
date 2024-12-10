<?php

namespace Database\Seeders;

use App\Models\Celulares;
use Illuminate\Database\Seeder;

class CelularesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Celulares::factory(10)->create();
    }
}
