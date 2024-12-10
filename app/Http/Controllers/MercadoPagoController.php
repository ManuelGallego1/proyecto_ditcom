<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        SDK::setAccessToken(config('services.mercadopago.token'));
    }

    public function createPreference(Request $request)
    {
        // Crear una preferencia
        $preference = new Preference;

        // Crear un ítem en la preferencia
        $item = new Item;
        $item->title = $request->input('title');
        $item->quantity = $request->input('quantity');
        $item->unit_price = $request->input('unit_price');
        $preference->items = [$item];

        // Guardar y obtener la URL de pago
        $preference->save();

        return response()->json([
            'id' => $preference->id,
            'url_pago' => $preference->sandbox_init_point,
        ]);
    }
}