<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaViewController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', true)->paginate(10);
        // return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        // return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $categoria = Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => true
        ]);

    }

    public function edit(Categoria $categoria)
    {
        // return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $categoria->update($request->all());

      
    }

    public function destroy(Categoria $categoria)
    {
       
    }
} 