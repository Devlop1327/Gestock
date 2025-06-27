<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoViewController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with(['producto', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $productos = Producto::where('activo', true)
            ->where('user_id', auth()->id())
            ->get();
        // return view('movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:500',
            'fecha' => 'required|date',
        ]);

        // Obtener el producto
        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock para salidas
        if ($request->tipo === 'salida' && $producto->stock < $request->cantidad) {
        // return back()->with('error', 'No hay suficiente stock disponible');
        }

        // Crear el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $request->producto_id,
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'observacion' => $request->motivo,
            'fecha' => $request->fecha,
            'user_id' => auth()->id(),
        ]);

        // Actualizar el stock del producto
        if ($request->tipo === 'entrada') {
            $producto->stock += $request->cantidad;
        } else {
            $producto->stock -= $request->cantidad;
        }
        $producto->save();

       
    }

    public function show(Movimiento $movimiento)
    {
    }
}