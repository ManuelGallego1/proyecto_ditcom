<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fijo;
use App\Models\SedeVendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FijoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $fijos = Fijo::orderBy('id', 'asc')->paginate($perPage);

        $data = [
            'fijos' => $fijos,
            'pagination' => [
                'current_page' => $fijos->currentPage(),
                'last_page' => $fijos->lastPage(),
                'per_page' => $fijos->perPage(),
                'total' => $fijos->total(),
            ],
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validar = Validator::make($request->all(), [
            'fecha_instalacion' => 'nullable|date',
            'fecha_legalizacion' => 'nullable|date',
            'servicios_adicionales' => 'required|string',
            'estrato' => 'required|in:1,2,3,4,5,6,NR',
            'cuenta' => 'required|integer',
            'OT' => 'required|integer',
            'tipo_producto' => 'required|in:residencial,pyme',
            'total_servicios' => 'nullable|in:0,1,2,3', // Permitir 0
            'total_adicionales' => 'nullable|in:0,1,2,3', // Permitir 0
            'cliente_cc' => 'required|string|exists:clientes,cc',
            'convergente' => 'required|string',
            'ciudad' => 'required|string',
            'vendedor_id' => 'required|exists:users,id',
        ]);

        // Si la validación falla
        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Convertir "0" en null si es necesario
        $totalServicios = $request->total_servicios == '0' ? null : $request->total_servicios;
        $totalAdicionales = $request->total_adicionales == '0' ? null : $request->total_adicionales;

        $sedeVendedor = SedeVendedor::where('vendedor_id', $request->vendedor_id)->first();

        if (! $sedeVendedor) {
            return response()->json([
                'message' => 'Error, no se encontró una sede asignada para el vendedor',
                'status' => 400,
            ], 400);
        }

        // Crear el registro de Fijo
        $fijo = Fijo::create([
            'fecha_instalacion' => $request->fecha_instalacion,
            'fecha_legalizacion' => $request->fecha_legalizacion,
            'servicios_adicionales' => $request->servicios_adicionales,
            'estrato' => $request->estrato,
            'cuenta' => $request->cuenta,
            'OT' => $request->OT,
            'tipo_producto' => $request->tipo_producto,
            'total_servicios' => $totalServicios,
            'total_adicionales' => $totalAdicionales,
            'cliente_cc' => $request->cliente_cc,
            'sede_id' => $sedeVendedor->sede_id,
            'vendedor_id' => $request->vendedor_id,
            'estado' => 'digitado',
            'convergente' => $request->convergente,
            'ciudad' => $request->ciudad,
        ]);

        // Comprobar si la creación fue exitosa
        if (! $fijo) {
            return response()->json([
                'message' => 'Error al crear el registro de fijo',
                'status' => 500,
            ], 500);
        }

        // Respuesta exitosa
        return response()->json([
            'fijo' => $fijo,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $fijo = Fijo::where('vendedor_id', $id)->get();

        if (! $fijo) {
            $data = [
                'message' => 'Error, fijo no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'fijo' => $fijo,
            'status' => 200,
        ];

        return response()->json($data, 200);

    }

    public function show2($id)
    {
        $fijo = Fijo::where('id', $id)->get();

        if (! $fijo) {
            $data = [
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'fijo' => $fijo,
            'status' => 200,
        ];

        return response()->json($data, 200);

    }

    public function show3($id)
    {
        // Buscar registros de ventas fijas por sede y cargar las relaciones asociadas (vendedor, cliente, sede)
        $fijos = Fijo::where('sede_id', $id)->with(['vendedor', 'cliente', 'sede'])->get();

        // Si no hay registros, devolver un mensaje de error
        if ($fijos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron registros.', 'status' => 404], 404);
        }

        // Devolver los registros encontrados con un estado 200
        return response()->json(['fijo' => $fijos, 'status' => 200], 200);
    }

    public function showbypyme()
    {
        $fijo = Fijo::where('tipo_producto', 'pyme')->get();
        if ($fijo->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron registros de fijo tipo pyme',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'fijo' => $fijo,
            'status' => 200,
        ], 200);
    }

    public function destroy($id)
    {
        $fijo = Fijo::find($id);

        if (! $fijo) {
            $data = [
                'message' => 'Error, fijo no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $fijo->delete();

        $data = [
            'message' => 'Registro de fijo eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $fijo = Fijo::find($id);

        if (! $fijo) {
            $data = [
                'message' => 'Error, fijo no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'fecha_instalacion' => 'nullable|date',
            'fecha_legalizacion' => 'nullable|date',
            'servicios_adicionales' => 'required|string',
            'estrato' => 'required|in:1,2,3,4,5,6,NR',
            'cuenta' => 'required|integer',
            'OT' => 'required|integer',
            'tipo_producto' => 'required|in:residencial,pyme',
            'total_servicios' => 'nullable|in:1,2,3',
            'total_adicionales' => 'nullable|in:1,2,3',
            'cliente_cc' => 'required|string|exists:clientes,cc',
            'sede_id' => 'required|exists:sede,id',
            'convergente' => 'required|string',
            'ciudad' => 'required|string',
            'vendedor_id' => 'required|exists:users,id',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $fijo->update($request->all());

        $data = [
            'message' => 'Registro de fijo actualizado',
            'fijo' => $fijo,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $fijo = Fijo::find($id);

        if (! $fijo) {
            return response()->json([
                'message' => 'Error, fijo no encontrado',
                'status' => 404,
            ], 404);
        }

        // Obtener solo los campos presentes en la solicitud
        $dataToValidate = array_filter($request->all());

        // Validar solo los campos presentes en la solicitud
        $validar = Validator::make($dataToValidate, [
            'fecha_instalacion' => 'nullable|date',
            'fecha_legalizacion' => 'nullable|date',
            'servicios_adicionales' => 'sometimes|string',
            'estrato' => 'sometimes|in:1,2,3,4,5,6,NR',
            'cuenta' => 'sometimes|integer',
            'OT' => 'sometimes|integer',
            'tipo_producto' => 'sometimes|in:residencial,pyme',
            'total_servicios' => 'nullable|in:1,2,3',
            'total_adicionales' => 'nullable|in:1,2,3',
            'cliente_cc' => 'sometimes|string|exists:clientes,cc',
            'convergente' => 'sometimes|string',
            'ciudad' => 'sometimes|string',
            'vendedor_id' => 'sometimes|exists:users,id',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Actualizar los campos permitidos presentes en la solicitud
        $fijo->update($dataToValidate);

        return response()->json([
            'message' => 'Registro de fijo actualizado parcialmente',
            'fijo' => $fijo,
            'status' => 200,
        ], 200);
    }

    public function getFijosByCoordinador($coordinadorId)
    {
        $fijos = Fijo::whereHas('sede', function ($query) use ($coordinadorId) {
            $query->where('coordinador_id', $coordinadorId);
        })->with(['vendedor', 'cliente', 'sede'])->get();

        if ($fijos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron registros.', 'status' => 404], 404);
        }

        return response()->json(['fijo' => $fijos, 'status' => 200], 200);
    }
}
