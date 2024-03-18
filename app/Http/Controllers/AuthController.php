<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthExceptions;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; //para autenticación
use Illuminate\Support\Facades\Hash; //para generar hash
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->get();
        return response()->json($users);
    }
    public function create(Request $request)
    {
        try {
            $this->validate_user($request, 0);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Usuario creado correctamente',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (AuthExceptions $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request  $request)
    {
        try {
            $this->validate_user($request, 1);
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'errors' => ['No autorizado']
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'Usuario accede correctamente',
                'data' => $user,
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (AuthExceptions $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Usuario sale correctamente',
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            "message" => "Eliminado con éxito",
        ]);
    }

    private function validate_user(Request $request, int $op)
    {
        //op = 0 si es crear y login en otro caso

        $rules = [
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:8']
        ];
        $messages = [
            'email.required' => 'El correo es un campo requerido',
            'email.max' => 'El correo debe tener un máximo de 100 caracteres',
            'email.email' => 'El correo debe ser una dirección válida',
            'password.required' => 'La contraseña es un campo requerido',
            'password.min' => 'La contraseña debe tener un mínimo de 8 caracteres'
        ];
        if ($op == 0) {
            $rules['name'] = ['required', 'string', 'max:100'];
            $rules['email'][] = 'unique:users';
            $messages['name.required'] = 'El nombre de usuario es un campo requerido';
            $messages['name.max'] = 'El nombre de usuario debe tener un máximo de 100 caracters';
            $messages['email.unique'] = "Este correo ya se encuentra registrado";
        }
        $validator = Validator::make($request->input(), $rules, $messages);
        if ($validator->fails()) {
            throw new AuthExceptions($validator->errors()->first());
        }
    }
}
