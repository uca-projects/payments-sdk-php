<?php

namespace Uca\Payments\Builders;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Uca\Payments\Services\ApiPaymentService;
use Uca\Payments\Models\ApiPaymentModel;
use Illuminate\Support\Str;

class ApiPaymentBuilder
{
    protected array $filters = [];

    // Relacion con modelo externo
    private ?Collection $clientModels;

    public function __construct(?Collection $clientModels = null)
    {
        $this->clientModels = $clientModels ?? null;
    }

    public static function byExternalReference(string $payment_gateway_id, string $externalReference): ?ApiPaymentModel
    {
        if (!Str::isUuid($payment_gateway_id) || empty($externalReference)) {
            return null;
        }

        $response = app(ApiPaymentService::class)->byExternalReference($payment_gateway_id, $externalReference);
        return app(ApiPaymentBuilder::class)->fetchOne($response);
    }

    public static function byTransactionId(string $payment_gateway_id, string $transaction_id): ?ApiPaymentModel
    {
        if (!Str::isUuid($payment_gateway_id) || empty($transaction_id)) {
            return null;
        }

        $response = app(ApiPaymentService::class)->byTransactionId($payment_gateway_id, $transaction_id);
        return app(ApiPaymentBuilder::class)->fetchOne($response);
    }

    public static function byPreferenceId(string $preference_id): ?ApiPaymentModel
    {
        if (empty($preference_id)) {
            return null;
        }

        $response = app(ApiPaymentService::class)->byPreferenceId($preference_id);
        return app(ApiPaymentBuilder::class)->fetchOne($response);
    }

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

    public function all(): Collection
    {
        return $this->get();
    }

    public function find(string $uuid): ?ApiPaymentModel
    {
        if (!Str::isUuid($uuid)) {
            return null;
        }

        $response = app(ApiPaymentService::class)->byId($uuid);
        return $this->fetchOne($response);
    }

    public function fetchMany(array $response): Collection
    {
        $items = collect($response['data'] ?? [])
            ->map(fn($item) => $this->fetchOne(['data' => $item]));
        return new Collection($items);
    }

    public function fetchOne(array $response): ?ApiPaymentModel
    {
        if (!isset($response['data']) || empty($response['data'])) {
            return null;
        }

        $data = is_array($response['data']) && isset($response['data'][0]) ? $response['data'][0] : $response['data'];

        // Si hay clientes, agregamos el atributo client
        if ($this->clientModels && isset($data['client_id'])) {
            $client = $this->clientModels->get($data['client_id']) ?? null;
            $data['client'] = $client->toArray();
        }

        return empty($data) ? null : new ApiPaymentModel($data);
    }

    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        $page = request('page', 1);

        $this->filters['per_page'] = ($perPage > 50) ? 50 : $perPage;
        $this->filters['page'] = $page;

        $response = app(ApiPaymentService::class)->search($this->filters);

        $data = $this->fetchMany($response);

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
