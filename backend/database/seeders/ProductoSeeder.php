<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\User;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriasAdmin = [
            'Electrónicos Premium',
            'Equipos Industriales',
            'Materiales Especializados',
            'Equipamiento Profesional',
            'Suministros Corporativos'
        ];

        foreach ($categoriasAdmin as $nombre) {
            Categoria::create([
                'nombre' => $nombre,
                'descripcion' => 'Categoría exclusiva para administradores',
                'activo' => true,
                'es_admin' => true
            ]);
        }

        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        $admin = User::where('email', 'admin@example.com')->first();

        for ($i = 1; $i <= 100; $i++) {
            $categoria = $categorias->random();
            $proveedor = $proveedores->random();
            Producto::firstOrCreate(
                ['codigo' => 'PROD-' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'nombre' => 'Producto ' . $i,
                    'descripcion' => 'Descripción del producto ' . $i,
                    'precio' => rand(10, 1000) + (rand(0, 100) / 100),
                    'stock' => rand(5, 100),
                    'stock_minimo' => rand(5, 15),
                    'categoria_id' => $categoria->id,
                    'proveedor_id' => $proveedor->id,
                    'user_id' => $admin ? $admin->id : null,
                    'activo' => true,
                    'es_admin' => true
                ]
            );
        }
    }
}
