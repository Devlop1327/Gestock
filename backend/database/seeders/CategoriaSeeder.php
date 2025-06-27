<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Electrónicos',
                'descripcion' => 'Productos electrónicos y gadgets',
                'activo' => true
            ],
            [
                'nombre' => 'Oficina',
                'descripcion' => 'Artículos de oficina y papelería',
                'activo' => true
            ],
            [
                'nombre' => 'Mobiliario',
                'descripcion' => 'Muebles y accesorios para oficina',
                'activo' => true
            ],
            [
                'nombre' => 'Software',
                'descripcion' => 'Licencias y programas informáticos',
                'activo' => true
            ],
            [
                'nombre' => 'Periféricos',
                'descripcion' => 'Accesorios y periféricos para computadoras',
                'activo' => true
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
} 