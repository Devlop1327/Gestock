<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Aplica los filtros a la consulta.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterable ?? []) && !empty($value)) {
                if (method_exists($this, 'scope' . ucfirst($field))) {
                    $query->{$field}($value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Aplica búsqueda por texto en los campos especificados.
     *
     * @param Builder $query
     * @param string $search
     * @param array $fields
     * @return Builder
     */
    public function scopeSearch($query, $search, array $fields)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($query) use ($search, $fields) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    /**
     * Aplica ordenamiento.
     *
     * @param Builder $query
     * @param string $field
     * @param string $direction
     * @return Builder
     */
    public function scopeOrder(Builder $query, $field, $direction = 'asc')
    {
        if (in_array($field, $this->orderable ?? [])) {
            return $query->orderBy($field, $direction);
        }

        return $query;
    }
} 