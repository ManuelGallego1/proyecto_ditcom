<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovilCollection;
use App\Models\Movil;
use App\Models\SedeVendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovilController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tipoProducto = $request->input('tipo_producto');

        $query = Movil::orderBy('id', 'asc');

        if ($tipoProducto) {
            $query->where('tipo_producto', $tipoProducto);
        }

        $moviles = $query->paginate($perPage);

        return (new MovilCollection($moviles))->additional([
            'pagination' => [
                'current_page' => $moviles->currentPage(),
                'last_page' => $moviles->lastPage(),
                'per_page' => $moviles->perPage(),
                'total' => $moviles->total(),
            ],
            'status' => 200,
        ]);
    }

    public function store(Request $request)
    {
        $validar = Validator::make($request->all(), [
            'min' => 'required|string|size:10',
            'imei' => 'required|string|size:15',
            'iccid' => 'required|string|size:17',
            'tipo' => 'required|in:kit prepago,kit financiado,wb,up grade,linea nueva,reposicion,portabilidad pre,portabilidad pos,venta de tecnologia,equipo pos',
            'plan_id' => 'required|exists:planes,id',
            'celulares_id' => 'required|exists:celulares,id',
            'cliente_cc' => 'required|exists:clientes,cc',
            'tipo_producto' => 'required|in:residencial,pyme',
            'factura' => 'required|string',
            'ingreso_caja' => 'required|string',
            'valor_recarga' => 'nullable',
            'valor_total' => 'required|numeric',
            'vendedor_id' => 'required|exists:users,id',
            'financiera' => 'required|in:crediminuto,celya,brilla,N/A',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Obtener la sede del vendedor automáticamente
        $sedeVendedor = SedeVendedor::where('vendedor_id', $request->vendedor_id)->first();

        if (!$sedeVendedor) {
            return response()->json([
                'message' => 'Error, no se encontró una sede asignada para el vendedor',
                'status' => 400,
            ], 400);
        }

        // Obtenemos el coordinador_id desde la sede
        $sede = $sedeVendedor->sede;

        if (!$sede || !$sede->coordinador_id) {
            return response()->json([
                'message' => 'Error, no se encontró un coordinador asignado para la sede',
                'status' => 400,
            ], 400);
        }

        // Crear el nuevo registro de móvil, asignando automáticamente la sede y el coordinador
        $movil = Movil::create([
            'min' => $request->min,
            'imei' => $request->imei,
            'iccid' => $request->iccid,
            'tipo' => $request->tipo,
            'plan_id' => $request->plan_id,
            'celulares_id' => $request->celulares_id,
            'cliente_cc' => $request->cliente_cc,
            'factura' => $request->factura,
            'ingreso_caja' => $request->ingreso_caja,
            'tipo_producto' => $request->tipo_producto,
            'valor_recarga' => $request->valor_recarga,
            'valor_total' => $request->valor_total,
            'vendedor_id' => $request->vendedor_id,
            'sede_id' => $sedeVendedor->sede_id, // Asignamos la sede automáticamente
            'financiera' => $request->financiera,
            'coordinador_id' => $sede->coordinador_id, // Asignamos el coordinador automáticamente
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'movil' => $movil,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $movil = Movil::where('vendedor_id', $id)->get();

        if (!$movil) {
            $data = [
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'movil' => $movil,
            'status' => 200,
        ];

        return response()->json($data, 200);

    }

    public function show2($id)
    {
        $movil = Movil::where('id', $id)->get();

        if (!$movil) {
            $data = [
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'movil' => $movil,
            'status' => 200,
        ];

        return response()->json($data, 200);

    }

    public function show3($id)
    {
        // Buscar registros de ventas móviles por sede y cargar las relaciones asociadas (vendedor, cliente, sede)
        $moviles = Movil::where('sede_id', $id)->with(['vendedor', 'cliente', 'sede', 'plan'])->get();

        // Si no hay registros, devolver un mensaje de error
        if ($moviles->isEmpty()) {
            return response()->json(['message' => 'No se encontraron registros.', 'status' => 404], 404);
        }

        // Devolver los registros encontrados con un estado 200
        return response()->json(['movil' => $moviles, 'status' => 200], 200);
    }

    public function destroy($id)
    {
        $movil = Movil::find($id);

        if (!$movil) {
            $data = [
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $movil->delete();

        $data = [
            'message' => 'Registro de móvil eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $movil = Movil::find($id);

        if (!$movil) {
            $data = [
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'min' => 'required|string|size:10',
            'imei' => 'required|string|size:15',
            'iccid' => 'required|string|size:17',
            'tipo' => 'required|in:kit prepago,kit financiado,wb,up grade,linea nueva,reposicion,portabilidad pre,portabilidad pos,venta de tecnologia,equipo pos',
            'plan_id' => 'required|exists:planes,id',
            'celulares_id' => 'required|exists:celulares,id',
            'cliente_cc' => 'required|exists:clientes,cc',
            'tipo_producto' => 'required|in:residencial,pyme',
            'factura' => 'required|string',
            'ingreso_caja' => 'required|string',
            'valor_recarga' => 'nullable',
            'valor_total' => 'required|numeric',
            'vendedor_id' => 'required|exists:users,id',
            'financiera' => 'required|in:crediminuto,celya,brilla,N/A',
            'estado' => 'required|in:pendiente,exitosa,rechazada,cancelada,terminada',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $movil->update($request->all());

        $data = [
            'message' => 'Registro de móvil actualizado',
            'movil' => $movil,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        // Buscar el registro de móvil por ID
        $movil = Movil::find($id);

        // Si no existe, devolver un error 404
        if (!$movil) {
            return response()->json([
                'message' => 'Error, móvil no encontrado',
                'status' => 404,
            ], 404);
        }

        $dataToValidate = array_filter($request->all());

        // Solo validar los campos que se han enviado en la solicitud
        $validar = Validator::make($request->all(), [
            'min' => 'sometimes|string|size:10',
            'imei' => 'sometimes|string|size:15',
            'iccid' => 'sometimes|string|size:17',
            'tipo' => 'sometimes|in:kit prepago,kit financiado,wb,up grade,linea nueva,reposicion,portabilidad pre,portabilidad pos,venta de tecnologia,equipo pos',
            'plan_id' => 'sometimes|exists:planes,id',
            'celulares_id' => 'sometimes|exists:celulares,id',
            'cliente_cc' => 'sometimes|exists:clientes,cc',
            'tipo_producto' => 'sometimes|in:residencial,pyme',
            'factura' => 'sometimes|string',
            'ingreso_caja' => 'sometimes|string',
            'valor_total' => 'sometimes|numeric',
            'vendedor_id' => 'sometimes|exists:users,id',
            'sede_id' => 'sometimes|exists:sede,id',
            'financiera' => 'sometimes|in:crediminuto,celya,brilla,N/A',
            'coordinador_id' => 'sometimes|exists:users,id',
            'estado' => 'sometimes|in:pendiente,exitosa,rechazada,cancelada,terminada', // Agregamos el campo estado
        ]);

        // Si la validación falla, devolver los errores
        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        // Actualizar solo los campos enviados y válidos
        $movil->update($dataToValidate);

        // Retornar una respuesta exitosa
        return response()->json([
            'message' => 'Registro de móvil actualizado parcialmente',
            'movil' => $movil,
            'status' => 200,
        ], 200);
    }

    public function getMovilesByCoordinador($coordinadorId)
    {
        $moviles = Movil::whereHas('sede', function ($query) use ($coordinadorId) {
            $query->where('coordinador_id', $coordinadorId);
        })->with(['vendedor', 'cliente', 'sede', 'plan'])->get();

        if ($moviles->isEmpty()) {
            return response()->json(['message' => 'No se encontraron registros.', 'status' => 404], 404);
        }

        return response()->json(['movil' => $moviles, 'status' => 200], 200);
    }

    public function showbypyme()
    {
        $movil = Movil::where('tipo_producto', 'pyme')->get();
        if ($movil->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron registros de movil tipo pyme',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'movil' => $movil,
            'status' => 200,
        ], 200);
    }
}
