<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'stock_minimo',
        'categoria_id',
        'proveedor_id',
        'user_id',
        'activo'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'activo' => 'boolean'
    ];

    protected $filterable = [
        'categoria_id',
        'proveedor_id',
        'stock_bajo',
        'precio_range',
        'stock_range'
    ];

    protected $orderable = [
        'codigo',
        'nombre',
        'precio',
        'stock',
        'created_at',
        'updated_at'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock <= stock_minimo');
    }

    public function scopePrecioRange($query, $range)
    {
        if (is_array($range) && count($range) === 2) {
            return $query->whereBetween('precio', $range);
        }
        return $query;
    }

    public function scopeStockRange($query, $range)
    {
        if (is_array($range) && count($range) === 2) {
            return $query->whereBetween('stock', $range);
        }
        return $query;
    }
}
