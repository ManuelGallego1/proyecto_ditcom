<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\MetaVenta;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
}
