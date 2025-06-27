<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoViewController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::select([
            'id',
            'codigo',
            'nombre',
            'precio',
            'stock',
            'stock_minimo',
            'categoria_id',
            'proveedor_id',
            'activo',
            'user_id'
        ])
        ->with(['categoria:id,nombre', 'proveedor:id,nombre']);

        // Mostrar solo productos del usuario autenticado si no es administrador
        if (!Auth::user()->esAdministrador()) {
            $query->where('user_id', Auth::id());
            $query->where('activo', true);
        }

        // Solo filtrar por activo si no es administrador
        if (!Auth::user()->esAdministrador()) {
            $query->where('activo', true);
        }

        // Búsqueda por nombre o código
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        // Aplicar filtros
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->has('proveedor_id') && $request->proveedor_id != '') {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        if ($request->has('stock_bajo') && $request->stock_bajo == '1') {
            $query->whereRaw('stock <= stock_minimo');
        }

        // Filtro de estado para administradores
        if (Auth::user()->esAdministrador() && $request->has('estado')) {
            if ($request->estado === 'activos') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivos') {
                $query->where('activo', false);
            }
        }

        $productos = $query->paginate(15)->withQueryString();
        
        $categorias = Categoria::select('id', 'nombre')
            ->where('activo', true)
            ->get();
        
        $proveedores = Proveedor::select('id', 'nombre')
            ->where('activo', true)
            ->get();

      
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        // return view('productos.show', compact('producto'));
    }

    public function movimientos($id)
    {
        $producto = Producto::with(['categoria', 'proveedor'])->findOrFail($id);
        $movimientos = $producto->movimientos()
            ->orderBy('fecha', 'desc')
            ->paginate(10);
        
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Verificar si hay movimientos asociados
        if ($producto->movimientos()->exists()) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede desactivar el producto porque tiene movimientos asociados');
        }

        $producto->activo = false;
        $producto->save();

       
    }

    public function reactivar($id)
    {
        $producto = Producto::findOrFail($id);
        
        if (!$producto->categoria->activo || !$producto->proveedor->activo) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede reactivar el producto porque su categoría o proveedor está inactivo');
        }

        $producto->activo = true;
        $producto->save();

    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $request->validate([
            'codigo' => 'required|string|max:20|unique:productos,codigo,' . $id,
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
        ]);

        // Verificar que la categoría y el proveedor estén activos
        $categoria = Categoria::findOrFail($request->categoria_id);
        $proveedor = Proveedor::findOrFail($request->proveedor_id);

        if (!$categoria->activo || !$proveedor->activo) {
        ;
        }

        $producto->update($request->all());

        
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        
        // Obtener los IDs seleccionados de la sesión
        $selectedCategoriaId = session('selected_categoria_id');
        $selectedProveedorId = session('selected_proveedor_id');
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:productos',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
        ]);

        // Verificar que la categoría y el proveedor estén activos
        $categoria = Categoria::findOrFail($request->categoria_id);
        $proveedor = Proveedor::findOrFail($request->proveedor_id);

        if (!$categoria->activo || !$proveedor->activo) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La categoría o el proveedor seleccionado no está activo');
        }

        // Crear el producto con el usuario autenticado
        Producto::create(array_merge(
            $request->all(),
            [
                'user_id' => Auth::id(),
                'activo' => true
            ]
        ));

        
    }
}
