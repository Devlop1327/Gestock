<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovimientoRequest;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $movimientos = Movimiento::with(['producto', 'usuario'])->get();
            return $this->successResponse($movimientos, 'Movimientos obtenidos exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener los movimientos: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovimientoRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $movimiento = Movimiento::create($request->validated());
            $producto = Producto::findOrFail($request->producto_id);

            if ($request->tipo === 'entrada') {
                $producto->increment('stock', $request->cantidad);
            } else {
                if ($producto->stock < $request->cantidad) {
                    throw new \Exception('No hay suficiente stock disponible');
                }
                $producto->decrement('stock', $request->cantidad);
            }

            DB::commit();
            return $this->successResponse($movimiento, 'Movimiento creado exitosamente', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al crear el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento): JsonResponse
    {
        try {
            $movimiento->load(['producto', 'usuario']);
            return $this->successResponse($movimiento, 'Movimiento obtenido exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovimientoRequest $request, Movimiento $movimiento): JsonResponse
    {
        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->producto_id);
            $cantidadAnterior = $movimiento->cantidad;
            $tipoAnterior = $movimiento->tipo;

            // Revertir el movimiento anterior
            if ($tipoAnterior === 'entrada') {
                $producto->decrement('stock', $cantidadAnterior);
            } else {
                $producto->increment('stock', $cantidadAnterior);
            }

            // Aplicar el nuevo movimiento
            $movimiento->update($request->validated());

            if ($request->tipo === 'entrada') {
                $producto->increment('stock', $request->cantidad);
            } else {
                if ($producto->stock < $request->cantidad) {
                    throw new \Exception('No hay suficiente stock disponible');
                }
                $producto->decrement('stock', $request->cantidad);
            }

            DB::commit();
            return $this->successResponse($movimiento, 'Movimiento actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento): JsonResponse
    {
        try {
            DB::beginTransaction();

            $producto = $movimiento->producto;
            if ($movimiento->tipo === 'entrada') {
                if ($producto->stock < $movimiento->cantidad) {
                    throw new \Exception('No se puede revertir el movimiento: stock insuficiente');
                }
                $producto->decrement('stock', $movimiento->cantidad);
            } else {
                $producto->increment('stock', $movimiento->cantidad);
            }

            $movimiento->delete();
            DB::commit();
            return $this->successResponse(null, 'Movimiento eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al eliminar el movimiento: ' . $e->getMessage());
        }
    }
}
