<?php

namespace Uca\Payments\Builders;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Uca\Payments\Services\ApiPaymentService;
use Uca\Payments\Models\ApiPaymentModel;

class ApiPaymentBuilder
{
    protected array $filters = [];

    public function where($column, $operator = null, $value = null, $boolean = 'and'): self
    {
        // Compatibilidad con Eloquent\Builder
        if (is_array($column)) {
            foreach ($column as $field => $val) {
                $this->filters[$field] = $val;
            }
        } else {
            $this->filters[$column] = $operator ?? $value;
        }

        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $this->filters[$field] = $values;
        return $this;
    }

    public function get(): Collection
    {
        $response = app(ApiPaymentService::class)->search($this->filters);
        return $this->fetchMany($response);
    }

    public function fetchMany(array $response): Collection
    {
        $items = collect($response['data'] ?? [])
            ->map(fn($item) => new ApiPaymentModel($item));
        return new Collection($items);
    }

    public function fetchOne(array $response): ?ApiPaymentModel
    {
        if (!isset($response['data'])) {
            return null;
        }

        $data = $response['data'];
        return new ApiPaymentModel(is_array($data) && isset($data[0]) ? $data[0] : $data);
    }

    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        $page = request('page', 1);

        $this->filters['per_page'] = $perPage;
        $this->filters['page'] = $page;

        $response = app(ApiPaymentService::class)->search($this->filters);

        $data = collect($response['data'] ?? [])
            ->map(fn($item) => new ApiPaymentModel($item));

        $total = $response['meta']['total'] ?? count($data);

        return new LengthAwarePaginator(
            $data,
            $total,
            $perPage,
            $page,
            [
                'path' => url()->current(),
                'query' => request()->query(),
            ]
        );
    }
}
