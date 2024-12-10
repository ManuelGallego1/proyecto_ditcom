<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Celulares;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CelularesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de elementos por página, por defecto 10
        $perPage = $request->input('per_page', 10);
        // Filtrar solo los celulares donde 'activo' sea true
        $celulares = Celulares::where('activo', true)->paginate($perPage);

        $data = [
            'celulares' => $celulares,
            'pagination' => [
                'current_page' => $celulares->currentPage(),
                'last_page' => $celulares->lastPage(),
                'total' => $celulares->total(),
                'per_page' => $celulares->perPage(),
            ],
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        // Validar los datos entrantes, sin incluir el campo 'activo'
        $validar = Validator::make($request->all(), [
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
        ]);

        // Si falla la validación, devolver errores
        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
            ], 400);
        }

        try {
            // Crear el nuevo registro del celular, estableciendo 'activo' en true por defecto
            $celular = Celulares::create([
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'activo' => true, // Establecer 'activo' en true por defecto
            ]);

            return response()->json([
                'message' => 'Celular creado con éxito',
                'celular' => $celular,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el registro del celular',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        // Solo mostrar celulares activos
        $celular = Celulares::where('id', $id)->where('activo', true)->first();

        if (! $celular) {
            $data = [
                'message' => 'Error, celular no encontrado o inactivo',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'celular' => $celular,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $celular = Celulares::find($id);

        if (! $celular) {
            $data = [
                'message' => 'Error, celular no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $celular->delete();

        $data = [
            'message' => 'Registro del celular eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $celular = Celulares::find($id);

        if (! $celular) {
            $data = [
                'message' => 'Error, celular no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $celular->update($request->all());

        $data = [
            'message' => 'Registro del celular actualizado',
            'celular' => $celular,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        // Buscar el celular por su ID
        $celular = Celulares::find($id);

        if (! $celular) {
            return response()->json([
                'message' => 'Error, celular no encontrado',
                'status' => 404,
            ], 404);
        }

        // Validar los datos recibidos, incluyendo 'activo'
        $validar = Validator::make($request->all(), [
            'marca' => 'string|max:50',
            'modelo' => 'string|max:50',
            'activo' => 'sometimes|boolean', // Validar el campo 'activo' como booleano
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Actualizar solo los campos enviados en la solicitud
        $celular->update($request->only(array_filter(array_keys($request->all()))));

        return response()->json([
            'message' => 'Registro del celular actualizado parcialmente',
            'celular' => $celular,
            'status' => 200,
        ], 200);
    }

    public function getModelosByMarca($marca)
    {
        // Obtener solo modelos activos
        $modelos = Celulares::where('marca', $marca)
            ->where('activo', true)
            ->get(['id', 'modelo']);

        return response()->json($modelos);
    }

    public function getMarcas()
    {
        // Obtener solo marcas que tengan celulares activos
        $marcas = Celulares::where('activo', true)->select('marca')->distinct()->pluck('marca');

        return response()->json($marcas);
    }

    public function storeMultiple(Request $request)
    {
        // Validar la estructura del JSON
        $validar = Validator::make($request->all(), [
            'celulares' => 'required|array',
            'celulares.*.marca' => 'required|string|max:50',
            'celulares.*.modelo' => 'required|string|max:50',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        // Intentar insertar todos los registros
        try {
            foreach ($request->celulares as $celularData) {
                Celulares::create($celularData + ['activo' => true]);  // Establecer activo como true por defecto
            }

            $data = [
                'message' => 'Todos los celulares fueron registrados con éxito',
                'status' => 201,
            ];

            return response()->json($data, 201);

        } catch (\Exception $e) {
            $data = [
                'message' => 'Ocurrió un error al registrar los celulares',
                'error' => $e->getMessage(),
                'status' => 500,
            ];

            return response()->json($data, 500);
        }
    }

    public function all()
    {
        $celulares = Celulares::all();

        $data = [
            'celulares' => $celulares,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
