<?php

namespace App\Mail;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockBajoNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $producto;

    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }

    public function build()
    {
        return $this->markdown('emails.stock-bajo')
            ->subject('Alerta: Stock Bajo - ' . $this->producto->nombre)
            ->with([
                'producto' => $this->producto,
                'stockActual' => $this->producto->stock,
                'stockMinimo' => $this->producto->stock_minimo
            ]);
    }
} 