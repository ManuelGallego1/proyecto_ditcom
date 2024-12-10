<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        SDK::setAccessToken(config('services.mercadopago.token'));
    }

    public function createPreference(Request $request)
    {
        // Configurar las credenciales de MercadoPago
        SDK::setAccessToken(config('services.mercadopago.token'));

        // Crear una preferencia
        $preference = new Preference();

        // Crear un ítem en la preferencia
        $item = new Item();
        $item->title = $request->input('title');
        $item->quantity = $request->input('quantity');
        $item->unit_price = $request->input('unit_price');
        $preference->items = array($item);

        // Guardar y obtener la URL de pago
        $preference->save();

        return response()->json([
            'id' => $preference->id,
            'url_pago' => $preference->sandbox_init_point
        ]);
    }
}