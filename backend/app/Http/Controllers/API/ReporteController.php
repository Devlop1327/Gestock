<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReporteController extends Controller
{
    public function getEstadisticasGenerales()
    {
        $totalProductos = Producto::count();
        $totalProveedores = Proveedor::count();
        $totalMovimientos = Movimiento::count();
        
        $productosBajoStock = Producto::where('stock', '<', DB::raw('stock_minimo'))->count();
        
        $ultimosMovimientos = Movimiento::with('producto')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'total_productos' => $totalProductos,
            'total_proveedores' => $totalProveedores,
            'total_movimientos' => $totalMovimientos,
            'productos_bajo_stock' => $productosBajoStock,
            'ultimos_movimientos' => $ultimosMovimientos
        ]);
    }

    public function getReporteMovimientos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $movimientos = Movimiento::with('producto', 'user')
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->get();

        $resumen = [
            'entradas' => $movimientos->where('tipo', 'entrada')->sum('cantidad'),
            'salidas' => $movimientos->where('tipo', 'salida')->sum('cantidad'),
            'total_movimientos' => $movimientos->count()
        ];

        return response()->json([
            'movimientos' => $movimientos,
            'resumen' => $resumen
        ]);
    }

    public function getReporteProductos()
    {
        $productos = Producto::with('categoria', 'proveedor')
            ->select('productos.*')
            ->get();

        $productosBajoStock = $productos->where('stock', '<', DB::raw('stock_minimo'));

        return response()->json([
            'productos' => $productos,
            'productos_bajo_stock' => $productosBajoStock
        ]);
    }
} 