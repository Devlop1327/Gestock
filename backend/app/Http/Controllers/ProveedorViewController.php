<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorViewController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::where('activo', true)->paginate(10);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'nit' => 'required|string|max:20|unique:proveedores',
            'email' => 'required|email|max:255|unique:proveedores',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        // Crear el proveedor
        $proveedor = Proveedor::create([
            'nombre' => $request->nombre,
            'contacto' => $request->contacto,
            'nit' => $request->nit,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'activo' => true
        ]);

        
    }

    public function edit(Proveedor $proveedor)
    {
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:proveedores,email,' . $proveedor->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:500',
        ]);

        $proveedor->update($request->all());

    }

    public function destroy(Proveedor $proveedor)
    {
        
    }
} 