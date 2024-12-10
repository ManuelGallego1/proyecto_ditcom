<?php

namespace Database\Seeders;

use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si hay usuarios con rol de 'coordinador' para asignarlos
        $coordinador = User::where('role', 'coordinador')->inRandomOrder()->first();

        // Si no hay un coordinador, crearlo
        if (! $coordinador) {
            $coordinador = User::create([
                'name' => 'Coordinador Ejemplo',
                'username' => 'coordinador@ejemplo.com',
                'password' => bcrypt('password'),
                'role' => 'coordinador',
            ]);
        }

        // Crear sedes con coordinador asignado
        Sede::create([
            'nombre' => 'Sede Central',
            'coordinador_id' => $coordinador->id,
            'activo' => true,
        ]);

        Sede::create([
            'nombre' => 'Sede Norte',
            'coordinador_id' => $coordinador->id,
            'activo' => true,
        ]);

        Sede::create([
            'nombre' => 'Sede Sur',
            'coordinador_id' => $coordinador->id,
            'activo' => false, // Esta sede está inactiva como ejemplo
        ]);
    }
}
