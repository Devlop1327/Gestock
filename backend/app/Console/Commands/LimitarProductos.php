<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;

class LimitarProductos extends Command
{
    protected $signature = 'productos:limitar50';
    protected $description = 'Deja solo 50 productos en la base de datos, eliminando el resto de forma segura.';

    public function handle()
    {
        $ids = Producto::orderBy('created_at')->pluck('id')->take(50);
        $eliminados = Producto::whereNotIn('id', $ids)->delete();
        $this->info("Productos eliminados: $eliminados. Solo quedan 50 productos en la base de datos.");
        return 0;
    }
}
