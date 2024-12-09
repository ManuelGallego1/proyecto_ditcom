<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    public function index()
    {
        $clientes = Clientes::all();

        $data = [
            'clientes' => $clientes,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validar = Validator::make($request->all(), [
            'p_nombre' => 'required',
            's_nombre' => 'nullable',
            'p_apellido' => 'required',
            's_apellido' => 'nullable',
            'email' => 'required|email',
            'numero' => 'required',
            'cc' => 'required|unique:clientes,cc', // Validar el campo cc
        ]);

        if ($validar->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ], 400);
        }

        $cliente = Clientes::create([
            'cc' => $request->cc,
            'p_nombre' => $request->p_nombre,
            's_nombre' => $request->s_nombre,
            'p_apellido' => $request->p_apellido,
            's_apellido' => $request->s_apellido,
            'email' => $request->email,
            'numero' => $request->numero,
        ]);

        return response()->json([
            'cliente' => $cliente,
            'status' => 201,
        ], 201);
    }

    public function show($cc)
    {
        $cliente = Clientes::where('cc', $cc)->first();

        if (! $cliente) {
            $data = [
                'message' => 'Error cliente no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'cliente' => $cliente,
            'status' => 200,
        ];

        return response()->json($data, 200);

    }

    public function destroy($id)
    {
        $cliente = Clientes::find($id);

        if (! $cliente) {
            $data = [
                'message' => 'Error cliente no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $cliente->delete();

        $data = [
            'message' => 'Cliente eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = Clientes::find($id);

        if (! $cliente) {
            $data = [
                'message' => 'Error cliente no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'p_nombre' => 'required',
            's_nombre' => 'nullable',
            'p_apellido' => 'required',
            's_apellido' => 'nullable',
            'email' => 'required|email',
            'numero' => 'required',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $cliente->p_nombre = $request->p_nombre;
        $cliente->s_nombre = $request->s_nombre;
        $cliente->p_apellido = $request->p_apellido;
        $cliente->s_apellido = $request->s_apellido;
        $cliente->email = $request->email;
        $cliente->numero = $request->numero;

        $cliente->save();

        $data = [
            'message' => 'Cliente actualizado',
            'cliente' => $cliente,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $cliente = Clientes::find($id);

        if (! $cliente) {
            $data = [
                'message' => 'Error cliente no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'p_nombre' => 'nullable',
            's_nombre' => 'nullable',
            'p_apellido' => 'nullable',
            's_apellido' => 'nullable',
            'email' => 'nullable|email',
            'numero' => 'nullable',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        if ($request->has('p_nombre')) {
            $cliente->p_nombre = $request->p_nombre;
        }
        if ($request->has('s_nombre')) {
            $cliente->s_nombre = $request->s_nombre;
        }
        if ($request->has('p_apellido')) {
            $cliente->p_apellido = $request->p_apellido;
        }
        if ($request->has('s_apellido')) {
            $cliente->s_apellido = $request->s_apellido;
        }
        if ($request->has('email')) {
            $cliente->email = $request->email;
        }
        if ($request->has('numero')) {
            $cliente->numero = $request->numero;
        }

        $cliente->save();

        $data = [
            'message' => 'Cliente actualizado',
            'cliente' => $cliente,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
