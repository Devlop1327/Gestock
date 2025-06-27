<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockBajoNotification;
use App\Traits\Cacheable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    use ApiResponse, LogActivity, Cacheable;

    private const CACHE_MINUTES = 60;

    private function validarStock($producto, $cantidad)
    {
        if ($producto->stock + $cantidad < 0) {
            throw new \Exception('No hay suficiente stock disponible');
        }

    }

    private function verificarStockBajo($producto)
    {
        if ($producto->stock <= $producto->stock_minimo) {
            // Enviar notificación por email
            Mail::to('admin@inventario.com')->send(new StockBajoNotification($producto));
            
            // Registrar en logs
            $this->logActivity('Alerta de stock bajo', [
                'producto_id' => $producto->id,
                'stock_actual' => $producto->stock,
                'stock_minimo' => $producto->stock_minimo
            ], 'warning');
        }
    }

    private function validarPrecio($precio)
    {
        if ($precio < 0) {
            throw new \Exception('El precio no puede ser negativo');
        }

        if ($precio > 1000000) {
            throw new \Exception('El precio excede el máximo permitido');
        }
    }

    public function index(Request $request): JsonResponse
    {
        try {
            \Log::info('USUARIO DEBUG', [
                'id' => auth()->id(),
                'user' => auth()->user(),
                'request' => $request->all()
            ]);
            \Log::info('Usuario autenticado en index productos:', [
                'id' => auth()->id(),
                'user' => auth()->user()
            ]);
            $page = request()->input('page', 1);
            $perPage = (int) request()->input('per_page', 10);
            if ($perPage < 1) $perPage = 10;
            $search = request()->input('search', '');
            $categoriaId = request()->input('categoria_id');
            $proveedorId = request()->input('proveedor_id');

            $cacheKey = $this->getCacheKey('productos.index', [
                $page,
                $perPage,
                $search,
                $categoriaId,
                $proveedorId,
                auth()->id()
            ]);

            // Desactivar caché para depuración de paginación
            $query = Producto::with(['categoria', 'proveedor']);

            // Si no es administrador, solo mostrar sus productos
            if (!auth()->user()->esAdmin()) {
                $query->where('user_id', auth()->id());
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('codigo', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%");
                });
            }

            if ($categoriaId) {
                $query->where('categoria_id', $categoriaId);
            }

            if ($proveedorId) {
                $query->where('proveedor_id', $proveedorId);
            }

            // Filtro de stock bajo
            if ($request->has('stock_bajo') && $request->stock_bajo == '1') {
                $query->whereRaw('stock <= stock_minimo');
            }

            $productos = $query->paginate($perPage);

            $this->logActivity('Listado de productos', [
                'total' => $productos->total(),
                'pagina' => $page,
                'por_pagina' => $perPage
            ]);

            return $this->successResponse($productos);
        } catch (\Exception $e) {
            $this->logError('Error al listar productos', $e);
            return $this->errorResponse('Error al obtener los productos',500);
        }
    }

   
    public function store(ProductoRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $this->validarPrecio($request->precio);
            $this->validarStock(new Producto($request->all()), $request->stock);
            
            $validator = Validator::make($request->all(), [
                'codigo' => 'required|string|max:20|unique:productos',
                'nombre' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:500',
                'precio' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'categoria_id' => 'required|exists:categorias,id',
                'proveedor_id' => 'required|exists:proveedores,id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Validar que el stock sea mayor o igual al stock mínimo
            if ($request->stock < $request->stock_minimo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El stock debe ser mayor o igual al stock mínimo',
                    'errors' => ['stock' => ['El stock debe ser mayor o igual al stock mínimo']]
                ], 422);
            }

            // Validar que el stock no exceda el stock máximo (20 veces el stock mínimo)
            $stock_maximo = $request->stock_minimo * 20;
            if ($request->stock > $stock_maximo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El stock no puede exceder el stock máximo',
                    'errors' => ['stock' => ['El stock no puede exceder el stock máximo']]
                ], 422);
            }

            // Verificar que la categoría y el proveedor estén activos
            $categoria = Categoria::findOrFail($request->categoria_id);
            $proveedor = Proveedor::findOrFail($request->proveedor_id);

            if (!$categoria->activo || !$proveedor->activo) {
                return response()->json([
                    'success' => false,
                    'message' => 'La categoría o el proveedor seleccionado no está activo'
                ], 400);
            }

            $producto = Producto::create(array_merge(
                $request->all(),
                ['activo' => true, 'user_id' => auth()->id()]
            ));

            $this->verificarStockBajo($producto);
            
            // Limpiar caché de listados
            $this->forgetCacheByPrefix('productos.index');
            
            $this->logActivity('Creación de producto', [
                'producto_id' => $producto->id,
                'datos' => $request->validated()
            ]);
            
            DB::commit();
            return $this->successResponse($producto, 'Producto creado exitosamente', 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            $this->logError('Error de validación', $e, $request->validated());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('Error al crear producto', $e, $request->validated());
            return $this->errorResponse('Error al crear el producto: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto): JsonResponse
    {
        try {
            // Si no es administrador, verificar que el producto sea suyo
            if (!auth()->user()->esAdmin() && $producto->user_id !== auth()->id()) {
                return $this->errorResponse('No tienes permiso para ver este producto', 403);
            }

            $cacheKey = $this->getCacheKey('productos.show', [$producto->id]);
            
            $producto = $this->getCached($cacheKey, self::CACHE_MINUTES, function () use ($producto) {
                return $producto->load(['categoria', 'proveedor']);
            });

            $this->logActivity('Consulta de producto', ['producto_id' => $producto->id]);
            return $this->successResponse($producto);
        } catch (\Exception $e) {
            $this->logError('Error al consultar producto', $e, ['producto_id' => $producto->id]);
            return $this->errorResponse('Error al obtener el producto', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, Producto $producto): JsonResponse
    {
        try {
            // Si no es administrador, verificar que el producto sea suyo
            if (!auth()->user()->esAdmin() && $producto->user_id !== auth()->id()) {
                return $this->errorResponse('No tienes permiso para actualizar este producto', 403);
            }

            DB::beginTransaction();
            
            $this->validarPrecio($request->precio);
            
            $diferenciaStock = $request->stock - $producto->stock;
            $this->validarStock($producto, $diferenciaStock);
            
            $datosAnteriores = $producto->toArray();
            $producto->update($request->validated());
            
            $this->verificarStockBajo($producto);
            
            // Limpiar caché
            $this->forgetCacheByPrefix('productos.index');
            $this->forgetCacheByPrefix('productos.show:' . $producto->id);
            
            $this->logActivity('Actualización de producto', [
                'producto_id' => $producto->id,
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => $request->validated()
            ]);
            
            DB::commit();
            return $this->successResponse($producto, 'Producto actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('Error al actualizar producto', $e, [
                'producto_id' => $producto->id,
                'datos' => $request->validated()
            ]);
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto): JsonResponse
    {
        try {
            // Si no es administrador, verificar que el producto sea suyo
            if (!auth()->user()->esAdmin() && $producto->user_id !== auth()->id()) {
                return $this->errorResponse('No tienes permiso para eliminar este producto', 403);
            }

            DB::beginTransaction();
            
            $datosProducto = $producto->toArray();
            $producto->delete();
            
            // Limpiar caché
            $this->forgetCacheByPrefix('productos.index');
            $this->forgetCacheByPrefix('productos.show:' . $producto->id);
            
            $this->logActivity('Eliminación de producto', [
                'producto_id' => $producto->id,
                'datos' => $datosProducto
            ]);
            
            DB::commit();
            return $this->successResponse(null, 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('Error al eliminar producto', $e, ['producto_id' => $producto->id]);
            return $this->errorResponse('Error al eliminar el producto', 500);
        }
    }

    public function ajustarStock(Producto $producto, $cantidad, $tipo): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $cantidad = $tipo === 'entrada' ? $cantidad : -$cantidad;
            $this->validarStock($producto, $cantidad);
            
            $producto->stock += $cantidad;
            $producto->save();
            
            $this->verificarStockBajo($producto);
            
            $this->logActivity('Ajuste de stock', [
                'producto_id' => $producto->id,
                'tipo' => $tipo,
                'cantidad' => abs($cantidad),
                'stock_anterior' => $producto->stock - $cantidad,
                'stock_nuevo' => $producto->stock
            ]);
            
            DB::commit();
            return $this->successResponse($producto, 'Stock ajustado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('Error al ajustar stock', $e, [
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'tipo' => $tipo
            ]);
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    protected function getCacheKey($prefix, $params = [])
    {
        return $prefix . ':' . md5(serialize($params));
    }

    protected function getCached($key, $minutes, $callback)
    {
        return Cache::remember($key, $minutes * 60, $callback);
    }

    protected function logActivity($message, $context = [])
    {
        Log::info($message, array_merge($context, [
            'user_id' => auth()->id(),
            'ip' => request()->ip()
        ]));
    }

    protected function logError($message, $exception)
    {
        Log::error($message, [
            'error' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => auth()->id(),
            'ip' => request()->ip()
        ]);
    }

    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    protected function forgetCacheByPrefix($prefix)
    {
        $cacheStore = Cache::getStore();
        $cachePrefix = config('cache.prefix') ? config('cache.prefix') . ':' . $prefix : $prefix;
        if ($cacheStore instanceof \Illuminate\Cache\RedisStore) {
            $redis = Cache::getRedis();
            $keys = $redis->keys($cachePrefix . '*');
            foreach ($keys as $key) {
                $redis->del($key);
            }
        }
        // Limpiar caché en File
        elseif ($cacheStore instanceof \Illuminate\Cache\FileStore) {
            $cachePath = config('cache.stores.file.path');
            $files = glob($cachePath . DIRECTORY_SEPARATOR . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $contents = @file_get_contents($file);
                    if ($contents !== false && strpos($contents, $cachePrefix) !== false) {
                        @unlink($file);
                    }
                }
            }
        }
        // Limpiar caché en Database
        elseif ($cacheStore instanceof \Illuminate\Cache\DatabaseStore) {
            // Elimina todas las filas cuyo key empiece con el prefijo
            \DB::table(config('cache.stores.database.table', 'cache'))
                ->where('key', 'like', $cachePrefix . '%')->delete();
        }
    }
}
