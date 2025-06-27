<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Índices para la tabla de usuarios
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email');
                $table->index('created_at');
            });
        }

        // Índices para la tabla de productos
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->index('nombre');
                $table->index('codigo');
                $table->index('categoria_id');
                $table->index('proveedor_id');
                $table->index('stock');
                $table->index('precio');
                $table->index('activo');
                $table->index('created_at');
            });
        }

        // Índices para la tabla de categorías
        if (Schema::hasTable('categorias')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->index('nombre');
                $table->index('created_at');
            });
        }

        // Índices para la tabla de proveedores
        if (Schema::hasTable('proveedores')) {
            Schema::table('proveedores', function (Blueprint $table) {
                $table->index('nombre');
                $table->index('email');
                $table->index('telefono');
                $table->index('created_at');
            });
        }

        // Índices para la tabla de movimientos
        if (Schema::hasTable('movimientos')) {
            Schema::table('movimientos', function (Blueprint $table) {
                $table->index('producto_id');
                $table->index('tipo');
                $table->index('fecha');
                $table->index('created_at');
            });
        }
    }

    public function down()
    {
        // Eliminar índices de la tabla de usuarios
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email']);
                $table->dropIndex(['created_at']);
            });
        }

        // Eliminar índices de la tabla de productos
        if (Schema::hasTable('productos')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropIndex(['nombre']);
                $table->dropIndex(['codigo']);
                $table->dropIndex(['categoria_id']);
                $table->dropIndex(['proveedor_id']);
                $table->dropIndex(['stock']);
                $table->dropIndex(['precio']);
                $table->dropIndex(['activo']);
                $table->dropIndex(['created_at']);
            });
        }

        // Eliminar índices de la tabla de categorías
        if (Schema::hasTable('categorias')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropIndex(['nombre']);
                $table->dropIndex(['created_at']);
            });
        }

        // Eliminar índices de la tabla de proveedores
        if (Schema::hasTable('proveedores')) {
            Schema::table('proveedores', function (Blueprint $table) {
                $table->dropIndex(['nombre']);
                $table->dropIndex(['email']);
                $table->dropIndex(['telefono']);
                $table->dropIndex(['created_at']);
            });
        }

        // Eliminar índices de la tabla de movimientos
        if (Schema::hasTable('movimientos')) {
            Schema::table('movimientos', function (Blueprint $table) {
                $table->dropIndex(['producto_id']);
                $table->dropIndex(['tipo']);
                $table->dropIndex(['fecha']);
                $table->dropIndex(['created_at']);
            });
        }
    }
}; 