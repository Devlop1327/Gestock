<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movimiento;
use App\Models\Producto;

class MovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::where('activo', true)->get();

        foreach ($productos as $producto) {
            // Crear entrada inicial
            Movimiento::create([
                'tipo' => 'entrada',
                'cantidad' => $producto->stock,
                'fecha' => now()->subDays(30),
                'producto_id' => $producto->id,
                'observacion' => 'Stock inicial'
            ]);

            // Crear algunas salidas aleatorias
            $salidas = rand(1, 3);
            for ($i = 0; $i < $salidas; $i++) {
                $cantidad = rand(1, min(3, $producto->stock));
                Movimiento::create([
                    'tipo' => 'salida',
                    'cantidad' => $cantidad,
                    'fecha' => now()->subDays(rand(1, 29)),
                    'producto_id' => $producto->id,
                    'observacion' => 'Venta regular'
                ]);
            }

            $entradas = rand(1, 2);
            for ($i = 0; $i < $entradas; $i++) {
                $cantidad = rand(1, 5);
                Movimiento::create([
                    'tipo' => 'entrada',
                    'cantidad' => $cantidad,
                    'fecha' => now()->subDays(rand(1, 29)),
                    'producto_id' => $producto->id,
                    'observacion' => 'Reposición de stock'
                ]);
            }
        }
    }
}
