<?php

namespace Uca\Payments\Filters;


class CommonFilters extends QueryFilter
{

    public function __construct(array $filterFields, string $table)
    {
        parent::__construct($filterFields, $table);
    }

    public function __call($method, $arguments): void
    {
        if (!isset($arguments[0])) {
            return;
        }
        $value = $arguments[0];

        // Soporte para rangos: min_* y max_*
        if (str_starts_with($method, 'min_')) {
            $field = substr($method, 4);
            $this->builder->where($field, '>=', $value);
            return;
        }

        if (str_starts_with($method, 'max_')) {
            $field = substr($method, 4);
            $this->builder->where($field, '<=', $value);
            return;
        }

        // Búsqueda parcial (like): si el nombre del campo termina en `_like`
        if (str_ends_with($method, '_like')) {
            $field = substr($method, 0, -5);
            $this->builder->whereLike($field, "%$value%");
            return;
        }

        // Filtro exacto (por defecto)
        $this->builder->where($method, $value);
    }
}
