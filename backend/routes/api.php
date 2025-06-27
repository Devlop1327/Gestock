<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\ProveedorController;
use App\Http\Controllers\API\ProductoController;
use App\Http\Controllers\API\MovimientoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ReporteController;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\API\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('login', [LoginController::class, 'apiLogin']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {

    // Cambiar contraseña del usuario autenticado
    Route::post('/user/change-password', [\App\Http\Controllers\API\UserController::class, 'changePassword']);
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('api.categorias.index');
    Route::get('/proveedores', [App\Http\Controllers\API\ProveedorController::class, 'index'])->name('api.proveedores.index');
    Route::get('/movimientos', [App\Http\Controllers\API\MovimientoController::class, 'index'])->name('api.movimientos.index');
    Route::get('/admin/reportes', [App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('api.admin.reportes.index');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [App\Http\Controllers\API\UserController::class, 'update']);

    Route::get('/reportes/estadisticas', [ReporteController::class, 'getEstadisticasGenerales']);
    Route::get('/reportes/movimientos', [ReporteController::class, 'getReporteMovimientos']);
    Route::get('/reportes/productos', [ReporteController::class, 'getReporteProductos']);

    Route::post('/productos/{producto}/imagen', [FileController::class, 'uploadProductImage']);
    Route::delete('/productos/{producto}/imagen', [FileController::class, 'deleteProductImage']);
    Route::get('/productos/{producto}/imagen', [FileController::class, 'getProductImage']);

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/stock-bajo', [DashboardController::class, 'stockBajo']);
    Route::get('/dashboard/movimientos-recientes', [DashboardController::class, 'movimientosRecientes']);

    Route::apiResource('productos', ProductoController::class)->names([
        'index' => 'api.productos.index',
        'store' => 'api.productos.store',
        'show' => 'api.productos.show',
        'update' => 'api.productos.update',
        'destroy' => 'api.productos.destroy',
    ]);
    Route::get('/productos/codigo/{codigo}', [ProductoController::class, 'findByCodigo']);
    Route::post('/productos/{producto}/movimiento', [ProductoController::class, 'registrarMovimiento']);

    // Categorías
    Route::apiResource('categorias', CategoriaController::class)->names([
        'index' => 'api.categorias.index',
        'store' => 'api.categorias.store',
        'show' => 'api.categorias.show',
        'update' => 'api.categorias.update',
        'destroy' => 'api.categorias.destroy',
    ]);

    // Proveedores
    Route::apiResource('proveedores', ProveedorController::class)->names([
        'index' => 'api.proveedores.index',
        'store' => 'api.proveedores.store',
        'show' => 'api.proveedores.show',
        'update' => 'api.proveedores.update',
        'destroy' => 'api.proveedores.destroy',
    ]);

    Route::apiResource('movimientos', MovimientoController::class)->only(['index', 'show'])->names([
        'index' => 'api.movimientos.index',
        'show' => 'api.movimientos.show',
    ]);
    Route::get('/movimientos/producto/{producto}', [MovimientoController::class, 'porProducto']);

    Route::post('/admin/users/{id}/change-password', [App\Http\Controllers\API\AdminUserController::class, 'changePassword']);
    Route::post('/admin/limpiar-cache', [AdminController::class, 'limpiarCache']);
});

Route::get('/roles', [App\Http\Controllers\API\AuthController::class, 'roles']);

// Rutas del Dashboard
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/dashboard/metrics', [DashboardController::class, 'getMetrics']);
});

