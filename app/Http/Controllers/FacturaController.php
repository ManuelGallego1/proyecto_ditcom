<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\MetaVenta;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    public function generarPDF($id)
    {
        $factura = Factura::findOrFail($id);

        // Save the factura
        $factura->save();

        // Generate the PDF
        $pdf = PDF::loadView('factura', compact('factura'));

        return $pdf->download('factura.pdf');
    }

    public function progresoVentas($tipo_venta)
    {
        $cantidadVentas = Factura::where('tipo_venta', $tipo_venta)->count();
        $metaVenta = MetaVenta::where('tipo_venta', $tipo_venta)->first();

        if ($metaVenta) {
            $progreso = ($cantidadVentas / $metaVenta->cantidad) * 100;
        } else {
            $progreso = 0;
        }

        return response()->json([
            'tipo_venta' => $tipo_venta,
            'cantidad_ventas' => $cantidadVentas,
            'meta_venta' => $metaVenta ? $metaVenta->cantidad : 0,
            'progreso' => $progreso,
        ]);
    }

    public function editarEstado($id, $nuevoEstado)
    {
        $factura = Factura::findOrFail($id);
        $factura->estado = $nuevoEstado;
        $factura->save();

        return response()->json([
            'message' => 'Estado de la factura actualizado correctamente',
            'factura' => $factura,
        ]);
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return response()->json([
            'message' => 'Factura eliminada correctamente',
        ]);
    }

    public function index()
    {
        $facturas = Factura::all();

        return response()->json($facturas);
    }

    public function store(Request $request)
    {
        $validar = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'asesor_id' => 'required|exists:users,id',
            'tipo_venta' => 'required',
            'valor' => 'required',
            'estado' => 'required',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
            ], 400);
        }

        $factura = Factura::create($request->all());

        return response()->json([
            'message' => 'Factura creada correctamente',
            'factura' => $factura,
        ], 201);
    }

    public function show($id)
    {
        $factura = Factura::findOrFail($id);

        return response()->json($factura);
    }

    public function update(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);

        $validar = Validator::make($request->all(), [
            'cliente_id' => 'sometimes|required|exists:clientes,id',
            'asesor_id' => 'sometimes|required|exists:users,id',
            'tipo_venta' => 'sometimes|required',
            'valor' => 'sometimes|required',
            'estado' => 'sometimes|required',
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
            ], 400);
        }

        $factura->update($request->all());

        return response()->json([
            'message' => 'Factura actualizada correctamente',
            'factura' => $factura,
        ]);
    }

    public function updatePartial(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);

        $factura->update($request->all());

        return response()->json([
            'message' => 'Factura actualizada correctamente',
            'factura' => $factura,
        ]);
    }
}
