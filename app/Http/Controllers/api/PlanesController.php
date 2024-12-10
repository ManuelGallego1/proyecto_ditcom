<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanesController extends Controller
{
    public function index()
    {
        // Filtrar solo los planes activos
        $planes = Planes::where('activo', true)->get();

        $data = [
            'planes' => $planes,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validar = Validator::make($request->all(), [
            'codigo' => 'required|int',
            'nombre' => 'required|string|max:150',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        // Crear el nuevo plan con 'activo' en true por defecto
        $plan = Planes::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'activo' => true,
        ]);

        if (! $plan) {
            $data = [
                'message' => 'Error al crear el registro del plan',
                'status' => 500,
            ];

            return response()->json($data, 500);
        }

        $data = [
            'plan' => $plan,
            'status' => 201,
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        // Buscar solo los planes activos por ID
        $plan = Planes::where('activo', true)->find($id);

        if (! $plan) {
            $data = [
                'message' => 'Error, plan no encontrado o inactivo',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'plan' => $plan,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $plan = Planes::find($id);

        if (! $plan) {
            $data = [
                'message' => 'Error, plan no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $plan->delete();

        $data = [
            'message' => 'Registro del plan eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $plan = Planes::find($id);

        if (! $plan) {
            $data = [
                'message' => 'Error, plan no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:100',
            'activo' => 'sometimes|boolean',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $plan->update($request->all());

        $data = [
            'message' => 'Registro del plan actualizado',
            'plan' => $plan,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $plan = Planes::find($id);

        if (! $plan) {
            return response()->json([
                'message' => 'Error, plan no encontrado',
                'status' => 404,
            ], 404);
        }

        $validar = Validator::make($request->all(), [
            'codigo' => 'string|max:50',
            'nombre' => 'string|max:100',
            'activo' => 'sometimes|boolean',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Actualizar solo los campos que se pasen en la solicitud
        $plan->update($request->only(array_filter(array_keys($request->all()))));

        return response()->json([
            'message' => 'Registro del plan actualizado parcialmente',
            'plan' => $plan,
            'status' => 200,
        ], 200);
    }

    public function getPlanByCodigo($codigo)
    {
        // Buscar el plan por código y que esté activo
        $plan = Planes::where('codigo', $codigo)->where('activo', true)->first();

        if (! $plan) {
            return response()->json([
                'message' => 'Plan no encontrado o inactivo',
                'status' => 404,
            ], 404);
        }

        return response()->json($plan);
    }

    public function getAllCodigos()
    {
        $codigos = Planes::where('activo', true)->select('codigo')->distinct()->pluck('codigo');

        return response()->json($codigos);
    }

    public function storeMultiple(Request $request)
    {
        // Validar la estructura del JSON
        $validar = Validator::make($request->all(), [
            'planes' => 'required|array',
            'planes.*.codigo' => 'required|string|max:50',
            'planes.*.nombre' => 'required|string|max:100',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Intentar insertar todos los registros
        try {
            foreach ($request->planes as $planData) {
                Planes::create(array_merge($planData, ['activo' => true]));
            }

            return response()->json([
                'message' => 'Todos los planes fueron registrados con éxito',
                'status' => 201,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al registrar los planes',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }

    public function all()
    {
        $planes = Planes::all();

        $data = [
            'planes' => $planes,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
