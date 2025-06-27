<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisoController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        $permisos = Permiso::all();
        
        return view('admin.permisos.index', compact('users', 'roles', 'permisos'));
    }

    public function asignarRol(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update([
            'role_id' => $request->role_id
        ]);

        return redirect()->back()->with('success', 'Rol asignado correctamente');
    }

    public function asignarPermisos(Request $request, Role $role)
    {
        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id'
        ]);

        DB::transaction(function () use ($role, $request) {
            $role->permisos()->sync($request->permisos ?? []);
        });

        return redirect()->back()->with('success', 'Permisos actualizados correctamente');
    }
} 