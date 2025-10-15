<?php

namespace Uca\Payments\Filters;

use Illuminate\Support\Facades\Schema;

abstract class QueryFilter
{
    protected $filterFields;
    protected $builder;
    protected array $allowedFilters;

    public function __construct(array $filterFields, string $table)
    {
        $this->filterFields = $filterFields;
        $this->allowedFilters = Schema::getColumnListing($table);
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            $this->$name($value);
        }

        return $this->builder;
    }

    protected function filters()
    {
        $input = $this->filterFields;
        return collect($input)
            ->filter(function ($value, $key) {
                // Normalizamos la clave quitando los modificadores
                $normalized = $key;

                if (str_starts_with($key, 'min_')) {
                    $normalized = substr($key, 4);
                } elseif (str_starts_with($key, 'max_')) {
                    $normalized = substr($key, 4);
                } elseif (str_ends_with($key, '_like')) {
                    $normalized = substr($key, 0, -5);
                }

                return in_array($normalized, $this->allowedFilters);
            })
            ->all();
    }
}
