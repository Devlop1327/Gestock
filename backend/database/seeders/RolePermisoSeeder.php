<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permiso;

class RolePermisoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permisos = [
            ['nombre' => 'ver_usuarios', 'descripcion' => 'Ver lista de usuarios'],
            ['nombre' => 'crear_usuarios', 'descripcion' => 'Crear nuevos usuarios'],
            ['nombre' => 'editar_usuarios', 'descripcion' => 'Editar usuarios existentes'],
            ['nombre' => 'eliminar_usuarios', 'descripcion' => 'Eliminar usuarios'],
            
            ['nombre' => 'ver_productos', 'descripcion' => 'Ver lista de productos'],
            ['nombre' => 'crear_productos', 'descripcion' => 'Crear nuevos productos'],
            ['nombre' => 'editar_productos', 'descripcion' => 'Editar productos existentes'],
            ['nombre' => 'eliminar_productos', 'descripcion' => 'Eliminar productos'],
            
            // Permisos de categorías
            ['nombre' => 'ver_categorias', 'descripcion' => 'Ver lista de categorías'],
            ['nombre' => 'crear_categorias', 'descripcion' => 'Crear nuevas categorías'],
            ['nombre' => 'editar_categorias', 'descripcion' => 'Editar categorías existentes'],
            ['nombre' => 'eliminar_categorias', 'descripcion' => 'Eliminar categorías'],
            
            // Permisos de proveedores
            ['nombre' => 'ver_proveedores', 'descripcion' => 'Ver lista de proveedores'],
            ['nombre' => 'crear_proveedores', 'descripcion' => 'Crear nuevos proveedores'],
            ['nombre' => 'editar_proveedores', 'descripcion' => 'Editar proveedores existentes'],
            ['nombre' => 'eliminar_proveedores', 'descripcion' => 'Eliminar proveedores'],
            
            // Permisos de movimientos
            ['nombre' => 'ver_movimientos', 'descripcion' => 'Ver movimientos de inventario'],
            ['nombre' => 'crear_movimientos', 'descripcion' => 'Registrar movimientos de inventario'],
            
            // Permisos de dashboard
            ['nombre' => 'ver_dashboard', 'descripcion' => 'Ver estadísticas del dashboard'],
            ['nombre' => 'ver_reportes', 'descripcion' => 'Ver y generar reportes']
        ];

        foreach ($permisos as $permiso) {
            Permiso::updateOrInsert(
                ['nombre' => $permiso['nombre']], // Match by unique field
                ['descripcion' => $permiso['descripcion']] // Update or insert description
            );
        }

        $roles = [
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo al sistema',
                'permisos' => [
                    'ver_usuarios', 'crear_usuarios', 'editar_usuarios', 'eliminar_usuarios',
                    'ver_productos', 'crear_productos', 'editar_productos', 'eliminar_productos',
                    'ver_categorias', 'crear_categorias', 'editar_categorias', 'eliminar_categorias',
                    'ver_proveedores', 'crear_proveedores', 'editar_proveedores', 'eliminar_proveedores',
                    'ver_movimientos', 'crear_movimientos',
                    'ver_dashboard', 'ver_reportes'
                ]
            ],
            [
                'nombre' => 'Usuario',
                'descripcion' => 'Acceso básico al sistema',
                'permisos' => [
                    'ver_productos',
                    'ver_categorias',
                    'ver_proveedores',
                    'ver_movimientos',
                    'crear_movimientos'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permisoNames = $roleData['permisos'];
            unset($roleData['permisos']);
            // Evita duplicados usando firstOrCreate
            $role = Role::firstOrCreate(
                ['nombre' => $roleData['nombre']],
                ['descripcion' => $roleData['descripcion']]
            );
            $permisosIds = Permiso::whereIn('nombre', $permisoNames)->pluck('id');
            $role->permisos()->syncWithoutDetaching($permisosIds);
        }
    }
}