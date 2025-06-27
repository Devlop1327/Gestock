<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }


            // Obtener el rol seleccionado
            $role = Role::find($request->role_id);
            $isAdmin = false;
            if ($role && ($role->nombre === 'Administrador' || strtolower($role->nombre) === 'admin')) {
                $isAdmin = true;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'activo' => true,
                'is_admin' => $isAdmin
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            // Cargar la relación role
            $user->load('role');

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => $user,
                    'role' => $user->role,
                    'permissions' => $user->role->permisos,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            
            // Verificar si el usuario está activo
            if (!$user->activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario inactivo'
                ], 403);
            }

            // Verificar si el usuario está bloqueado
            if ($user->bloqueado_hasta && $user->bloqueado_hasta > now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario bloqueado temporalmente',
                    'blocked_until' => $user->bloqueado_hasta
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            // Resetear intentos de login y actualizar último acceso
            $user->resetearIntentosLogin();
            $user->ultimo_acceso = now();
            $user->save();

            // Cargar la relación role y sus permisos
            $user->load('role.permisos');

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => $user,
                    'role' => $user->role,
                    'permissions' => $user->role->permisos,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            // Cargar el usuario con su rol y permisos
            $user = $request->user();
            $user->load('role.permisos');

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'role' => $user->role,
                    'permissions' => $user->role->permisos
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function roles()
    {
        return response()->json(Role::select('id', 'nombre')->get());
    }
} 