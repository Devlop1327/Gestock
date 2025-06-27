<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Movimiento;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Traits\ApiResponse;
use App\Traits\LogActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiResponse, LogActivity;

    public function getMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'productos' => $this->getProductMetrics(),
                'movimientos' => $this->getMovementMetrics(),
                'alertas' => $this->getAlerts(),
                'tendencias' => $this->getTrends()
            ];

            $this->logActivity('Consulta de métricas del dashboard');
            return $this->successResponse($metrics);
        } catch (\Exception $e) {
            $this->logError('Error al obtener métricas', $e);
            return $this->errorResponse('Error al obtener las métricas', 500);
        }
    }

    private function getProductMetrics()
    {
        return [
            'total' => Producto::count(),
            'stock_bajo' => Producto::where('stock', '<=', DB::raw('stock_minimo'))->count(),
            'sin_stock' => Producto::where('stock', 0)->count(),
            'valor_total' => Producto::sum(DB::raw('stock * precio')),
            'por_categoria' => Categoria::withCount('productos')->get()
        ];
    }

    private function getMovementMetrics()
    {
        $hoy = Carbon::today();
        $semana = Carbon::today()->subDays(7);
        $mes = Carbon::today()->subDays(30);

        return [
            'hoy' => [
                'entradas' => Movimiento::whereDate('fecha', $hoy)
                    ->where('tipo', 'entrada')
                    ->sum('cantidad'),
                'salidas' => Movimiento::whereDate('fecha', $hoy)
                    ->where('tipo', 'salida')
                    ->sum('cantidad')
            ],
            'ultima_semana' => [
                'entradas' => Movimiento::whereDate('fecha', '>=', $semana)
                    ->where('tipo', 'entrada')
                    ->sum('cantidad'),
                'salidas' => Movimiento::whereDate('fecha', '>=', $semana)
                    ->where('tipo', 'salida')
                    ->sum('cantidad')
            ],
            'ultimo_mes' => [
                'entradas' => Movimiento::whereDate('fecha', '>=', $mes)
                    ->where('tipo', 'entrada')
                    ->sum('cantidad'),
                'salidas' => Movimiento::whereDate('fecha', '>=', $mes)
                    ->where('tipo', 'salida')
                    ->sum('cantidad')
            ]
        ];
    }

    private function getAlerts()
    {
        return [
            'stock_bajo' => Producto::with(['categoria', 'proveedor'])
                ->where('stock', '<=', DB::raw('stock_minimo'))
                ->orderBy('stock', 'asc')
                ->limit(5)
                ->get(),
            'movimientos_recientes' => Movimiento::with(['producto', 'usuario'])
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    private function getTrends()
    {
        $ultimos_30_dias = Carbon::today()->subDays(30);
        
        return [
            'movimientos_diarios' => Movimiento::select(
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('SUM(CASE WHEN tipo = "entrada" THEN cantidad ELSE 0 END) as entradas'),
                DB::raw('SUM(CASE WHEN tipo = "salida" THEN cantidad ELSE 0 END) as salidas')
            )
            ->whereDate('fecha', '>=', $ultimos_30_dias)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get(),
            
            'productos_mas_movidos' => Producto::select('productos.*')
                ->join('movimientos', 'productos.id', '=', 'movimientos.producto_id')
                ->selectRaw('productos.*, SUM(movimientos.cantidad) as total_movimientos')
                ->whereDate('movimientos.fecha', '>=', $ultimos_30_dias)
                ->groupBy('productos.id')
                ->orderBy('total_movimientos', 'desc')
                ->limit(5)
                ->get()
        ];
    }
} 