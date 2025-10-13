<?php

namespace Uca\PaymentsSharedClass\Filters;

use Illuminate\Http\Request;

class CommonFilters extends QueryFilter
{
    protected array $columnMap;

    public function __construct(Request $request, array $columnMap = [])
    {
        parent::__construct($request);
        $this->columnMap = $columnMap;
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

            if ($column = $this->column($field)) {
                $this->builder->where($column, '>=', $value);
            }
            return;
        }

        if (str_starts_with($method, 'max_')) {
            $field = substr($method, 4);
            if ($column = $this->column($field)) {
                $this->builder->where($column, '<=', $value);
            }
            return;
        }

        // Búsqueda parcial (like): si el nombre del campo termina en `_like`
        if (str_ends_with($method, '_like')) {
            $field = substr($method, 0, -5);
            if ($column = $this->column($field)) {
                $this->builder->whereLike($column, "%$value%");
            }
            return;
        }

        // Filtro exacto (por defecto)
        if ($column = $this->column($method)) {
            $this->builder->where($column, $value);
        }
    }

    protected function column(string $alias): ?string
    {
        return $this->columnMap[$alias] ?? null;
    }
}
