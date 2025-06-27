<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TestController extends Controller
{
    public function limpiarCache(Request $request)
    {
        // Solo permitir a administradores
        if (!$request->user() || !$request->user()->esAdmin()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }
        Artisan::call('cache:clear');
        return response()->json(['success' => true, 'message' => 'Caché limpiado correctamente']);
    }

    public function testAdmin(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tienes acceso como administrador',
            'user' => $request->user(),
            'permisos' => $request->user()->role->permisos
        ]);
    }

    public function testUsuario(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tienes acceso como usuario estándar',
            'user' => $request->user(),
            'permisos' => $request->user()->role->permisos
        ]);
    }

    public function testProductos(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tienes acceso a la gestión de productos',
            'user' => $request->user(),
            'tiene_permiso_ver' => $request->user()->tienePermiso('ver_productos'),
            'tiene_permiso_crear' => $request->user()->tienePermiso('crear_productos'),
            'tiene_permiso_editar' => $request->user()->tienePermiso('editar_productos'),
            'tiene_permiso_eliminar' => $request->user()->tienePermiso('eliminar_productos')
        ]);
    }

    public function getUsuarios(Request $request)
    {
        $usuarios = User::with('role')->get();
        return response()->json([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }
}