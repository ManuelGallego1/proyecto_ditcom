<?php

namespace App\Http\Controllers;

use App\Models\MetaVenta;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    public function index()
    {
        $metas = MetaVenta::all();

        return response()->json($metas);
    }

    public function store(Request $request)
    {
        $meta = MetaVenta::create($request->all());

        return response()->json($meta, 201);
    }

    public function show($id)
    {
        $meta = MetaVenta::find($id);
        if (is_null($meta)) {
            return response()->json(['message' => 'Meta not found'], 404);
        }

        return response()->json($meta);
    }

    public function update(Request $request, $id)
    {
        $meta = MetaVenta::find($id);
        if (is_null($meta)) {
            return response()->json(['message' => 'Meta not found'], 404);
        }
        $meta->update($request->all());

        return response()->json($meta);
    }

    public function destroy($id)
    {
        $meta = MetaVenta::find($id);
        if (is_null($meta)) {
            return response()->json(['message' => 'Meta not found'], 404);
        }
        $meta->delete();

        return response()->json(['message' => 'Meta deleted successfully']);
    }
}
