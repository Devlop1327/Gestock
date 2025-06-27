<?php

namespace Database\Factories;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovimientoFactory extends Factory
{
    protected $model = Movimiento::class;

    public function definition(): array
    {
        return [
            'tipo' => $this->faker->randomElement(['entrada', 'salida']),
            'cantidad' => $this->faker->numberBetween(1, 10),
            'fecha' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'producto_id' => Producto::factory(),
            'observacion' => $this->faker->sentence(),
        ];
    }
} 