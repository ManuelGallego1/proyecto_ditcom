<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SedeVendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SedeVendedorController extends Controller
{
    // Método para listar todas las asignaciones de vendedores a sedes
    public function index()
    {
        // Obtener todas las asignaciones con las relaciones de vendedor y sede
        $sedeVendedores = SedeVendedor::with(['vendedor', 'sede'])->get();

        // Formatear la respuesta para incluir los nombres
        $formattedSedeVendedores = $sedeVendedores->map(function ($sedeVendedor) {
            return [
                'id' => $sedeVendedor->id,
                'vendedor_name' => $sedeVendedor->vendedor ? $sedeVendedor->vendedor->name : 'Vendedor no asignado', // Verificar si el vendedor existe
                'sede_name' => $sedeVendedor->sede ? $sedeVendedor->sede->nombre : 'Sede no asignada', // Verificar si la sede existe
            ];
        });

        return response()->json([
            'sedeVendedores' => $formattedSedeVendedores,
            'status' => 200,
        ], 200);
    }

    // Método para crear una nueva asignación de vendedor a sede
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validar = Validator::make($request->all(), [
            'vendedor_id' => 'required|exists:users,id',
            'sede_id' => 'required|exists:sede,id',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Crear la asignación en la tabla sede_vendedor
        $sedeVendedor = SedeVendedor::create($request->all());

        return response()->json([
            'message' => 'Asignación creada con éxito',
            'sedeVendedor' => $sedeVendedor,
            'status' => 201,
        ], 201);
    }

    // Método para mostrar una asignación específica por ID
    public function show($id)
    {
        $sedeVendedor = SedeVendedor::find($id);

        if (! $sedeVendedor) {
            return response()->json([
                'message' => 'Asignación no encontrada',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'sedeVendedor' => $sedeVendedor,
            'status' => 200,
        ], 200);
    }

    // Método para actualizar una asignación existente
    public function update(Request $request, $id)
    {
        $sedeVendedor = SedeVendedor::find($id);

        if (! $sedeVendedor) {
            return response()->json([
                'message' => 'Asignación no encontrada',
                'status' => 404,
            ], 404);
        }

        // Validar los datos recibidos
        $validar = Validator::make($request->all(), [
            'vendedor_id' => 'sometimes|required|exists:users,id',
            'sede_id' => 'sometimes|required|exists:sedes,id',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Actualizar la asignación
        $sedeVendedor->update($request->all());

        return response()->json([
            'message' => 'Asignación actualizada con éxito',
            'sedeVendedor' => $sedeVendedor,
            'status' => 200,
        ], 200);
    }

    // Método para eliminar una asignación
    public function destroy($id)
    {
        $sedeVendedor = SedeVendedor::find($id);

        if (! $sedeVendedor) {
            return response()->json([
                'message' => 'Asignación no encontrada',
                'status' => 404,
            ], 404);
        }

        // Eliminar la asignación
        $sedeVendedor->delete();

        return response()->json([
            'message' => 'Asignación eliminada con éxito',
            'status' => 200,
        ], 200);
    }
}
