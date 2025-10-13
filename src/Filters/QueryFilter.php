<?php

namespace Uca\PaymentsSharedClass\Filters;

use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;
    protected $builder;
    protected array $allowedFilters = ['external_ref', 'operation_id', 'date', 'card', 'status_resolve', 'owner', 'amount'];

    public function __construct(Request $request)
    {
        $this->request = $request;
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
        $input = $this->request->all();

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
