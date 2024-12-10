<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SedesCollection;
use App\Http\Resources\SedesResource;
use App\Models\Sedes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SedesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $sedes = Sedes::with('coordinador')
            ->where('activo', true)
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        return (new SedesCollection($sedes))->additional([
            'pagination' => [
                'current_page' => $sedes->currentPage(),
                'last_page' => $sedes->lastPage(),
                'per_page' => $sedes->perPage(),
                'total' => $sedes->total(),
            ],
            'status' => 200,
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos entrantes
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'coordinador_id' => 'required|exists:users,id',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        // Crear la nueva sede con el valor 'activo' en true
        $sede = Sedes::create([
            'nombre' => $request->nombre,
            'coordinador_id' => $request->coordinador_id,
            'activo' => true, // Establecer el valor de 'activo' en true
        ]);

        if (! $sede) {
            $data = [
                'message' => 'Error al crear la sede',
                'status' => 500,
            ];

            return response()->json($data, 500);
        }

        $data = [
            'sede' => new SedesResource($sede),
            'status' => 201,
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        // Buscar solo las sedes activas por ID
        $sede = Sedes::where('id', $id)->where('activo', true)->first();

        if (! $sede) {
            $data = [
                'message' => 'Error, sede no encontrada o inactiva',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'sede' => new SedesResource($sede),
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function show2($id)
    {
        // Buscar sedes activas por coordinador_id
        $sedes = Sedes::where('coordinador_id', $id)->where('activo', true)->get();

        if ($sedes->isEmpty()) {
            $data = [
                'message' => 'Error, sede no encontrada o inactiva',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'sede' => new SedesResource($sedes),
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $sede = Sedes::find($id);

        if (! $sede) {
            $data = [
                'message' => 'Error, sede no encontrada',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $sede->delete();

        $data = [
            'message' => 'Sede eliminada',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $sede = Sedes::find($id);

        if (! $sede) {
            $data = [
                'message' => 'Error, sede no encontrada',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'coordinador_id' => 'required|exists:users,id',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $sede->nombre = $request->nombre;
        $sede->coordinador_id = $request->coordinador_id;

        $sede->save();

        $data = [
            'message' => 'Sede actualizada',
            'sede' => new SedesResource($sede),
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $sede = Sedes::find($id);

        if (! $sede) {
            return response()->json([
                'message' => 'Error, sede no encontrada',
                'status' => 404,
            ], 404);
        }

        $validar = Validator::make($request->all(), [
            'nombre' => 'nullable|string',
            'coordinador_id' => 'nullable|exists:users,id',
            'activo' => 'nullable|boolean',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        if ($request->has('nombre')) {
            $sede->nombre = $request->nombre;
        }
        if ($request->has('coordinador_id')) {
            $sede->coordinador_id = $request->coordinador_id;
        }
        if ($request->has('activo')) {
            $sede->activo = $request->activo;
        }

        $sede->save();

        return response()->json([
            'message' => 'Sede actualizada',
            'sede' => new SedesResource($sede),
            'status' => 200,
        ], 200);
    }

    public function all()
    {
        $sedes = Sedes::with('coordinador')
            ->get()
            ->map(function ($sede) {
                return [
                    'id' => $sede->id,
                    'nombre' => $sede->nombre,
                    'coordinador_id' => $sede->coordinador_id,
                    'coordinador_name' => User::find($sede->coordinador_id)->name,
                    'activo' => $sede->activo,
                ];
            });

        $data = [
            'sedes' => new SedesCollection($sedes),
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
