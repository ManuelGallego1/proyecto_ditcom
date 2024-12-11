<?php

namespace Database\Seeders;

use App\Models\Clientes;
use App\Models\Fijo;
use App\Models\Sedes;
use App\Models\User;
use Illuminate\Database\Seeder;

class FijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cliente = Clientes::inRandomOrder()->first();
        $sede = Sedes::inRandomOrder()->first();
        $vendedor = User::where('role', 'vendedor')->inRandomOrder()->first();

        // Verificar que los modelos no sean null antes de proceder
        if ($cliente && $sede && $vendedor) {
            Fijo::create([
                'fecha_instalacion' => '2024-12-01',
                'fecha_legalizacion' => '2024-12-02',
                'servicios_adicionales' => 'Servicio A, Servicio B',
                'estrato' => 3,
                'cuenta' => '1234567890',
                'OT' => 'OT123',
                'tipo_producto' => 'Fibra Óptica',
                'total_servicios' => 2000,
                'total_adicionales' => 300,
                'cliente_cc' => $cliente->cc, // Cliente aleatorio
                'sede_id' => $sede->id, // Sede aleatoria
                'vendedor_id' => $vendedor->id, // Vendedor aleatorio
                'estado' => 'activo',
                'convergente' => 'Sí',
                'ciudad' => 'Medellín',
            ]);
        } else {
            echo "Error: No se encontró un cliente, sede o vendedor para crear el registro Fijo.\n";
        }

        // Puedes crear más registros si lo deseas, siguiendo la misma lógica de verificación
        $cliente2 = Clientes::inRandomOrder()->first();
        $sede2 = Sedes::inRandomOrder()->first();
        $vendedor2 = User::where('role', 'vendedor')->inRandomOrder()->first();

        if ($cliente2 && $sede2 && $vendedor2) {
            Fijo::create([
                'fecha_instalacion' => '2024-12-03',
                'fecha_legalizacion' => '2024-12-04',
                'servicios_adicionales' => 'Servicio C, Servicio D',
                'estrato' => 4,
                'cuenta' => '0987654321',
                'OT' => 'OT456',
                'tipo_producto' => 'ADSL',
                'total_servicios' => 1500,
                'total_adicionales' => 400,
                'cliente_cc' => $cliente2->cc,
                'sede_id' => $sede2->id,
                'vendedor_id' => $vendedor2->id,
                'estado' => 'activo',
                'convergente' => 'No',
                'ciudad' => 'Bogotá',
            ]);
        } else {
            echo "Error: No se encontró un cliente, sede o vendedor para crear el registro Fijo.\n";
        }
    }
}
