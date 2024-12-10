<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'super')->get();

        $data = [
            'users' => $users,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validar = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,asesor,coordinador,super',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'activo' => true,
        ]);

        if (! $user) {
            $data = [
                'message' => 'Error al crear el usuario',
                'status' => 500,
            ];

            return response()->json($data, 500);
        }

        $data = [
            'user' => $user,
            'status' => 201,
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'user' => $user,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $user->delete();

        $data = [
            'message' => 'Usuario eliminado',
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:admin,asesor,coordinador,super',
            'activo' => 'required|boolean',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        $user->name = $request->name;
        $user->username = $request->username; // Actualizar username
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->activo = $request->activo;

        $user->save();

        $data = [
            'message' => 'Usuario actualizado',
            'user' => $user,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $validar = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:admin,asesor,coordinador,super',
            'activo' => 'nullable|boolean',
        ]);

        if ($validar->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validar->errors(),
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('username')) {
            $user->username = $request->username; // Actualizar username si está presente
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('role')) {
            $user->role = $request->role;
        }
        if ($request->has('activo')) {
            $user->activo = $request->activo;
        }

        $user->save();

        $data = [
            'message' => 'Usuario actualizado parcialmente',
            'user' => $user,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }

    public function filterByRole($role)
    {
        // Validar que el rol sea uno de los permitidos
        $validRoles = ['vendedor', 'coordinador']; // Ajusta estos roles según tu aplicación

        if (! in_array($role, $validRoles)) {
            $data = [
                'message' => 'Rol no válido',
                'status' => 400,
            ];

            return response()->json($data, 400);
        }

        // Obtener los usuarios que tienen el rol especificado
        $users = User::where('role', $role)->get();

        if ($users->isEmpty()) {
            $data = [
                'message' => 'No se encontraron usuarios con el rol especificado',
                'status' => 404,
            ];

            return response()->json($data, 404);
        }

        $data = [
            'users' => $users,
            'status' => 200,
        ];

        return response()->json($data, 200);
    }
}
