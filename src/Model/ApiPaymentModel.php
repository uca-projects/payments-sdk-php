<?php

namespace Uca\PaymentsSharedClass\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Uca\PaymentsSharedClass\Builders\ApiPaymentBuilder;
use Uca\PaymentsSharedClass\Data\ClientData;
use Uca\PaymentsSharedClass\Data\ItemData;
use Uca\PaymentsSharedClass\Data\PayerData;
use Uca\PaymentsSharedClass\Data\PaymentCardData;
use Uca\PaymentsSharedClass\Data\PaymentDetailData;
use Uca\PaymentsSharedClass\Data\PaymentGatewayData;
use Uca\PaymentsSharedClass\Data\PaymentIntentionData;
use Uca\PaymentsSharedClass\ervices\ApiPaymentService;

class ApiPaymentModel extends Model
{
    // Simula modelo Eloquent sin tabla
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $table = null;

    // Objetos anidados
    public ?PayerData $payer = null;
    public array $items = [];
    public ?PaymentDetailData $paymentDetail = null;
    public ?PaymentIntentionData $paymentIntention = null;
    public ?PaymentCardData $paymentCard = null;
    public ?PaymentGatewayData $paymentGateway = null;
    public ?ClientData $client = null;

    protected $fillable = [
        'id',
        'client_id',
        'preference_id',
        'payment_gateway_id',
        'external_reference',
        'gateway_transaction_id',
        'client_domain',
        'amount',
        'currency',
        'status',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setPaymentCard($attributes['payment_card'] ?? null);
        $this->setPaymentIntention($attributes['payment_intention'] ?? null);
        $this->setPaymentDetail($attributes['payment_detail'] ?? null);
        $this->setPayer($attributes['payer'] ?? null);
        $this->setItems($attributes['items'] ?? null);
        $this->setPayer($attributes['client'] ?? null);
        $this->setItems($attributes['payment_gateway'] ?? null);
    }

    public static function query(): ApiPaymentBuilder
    {
        return new ApiPaymentBuilder();
    }

    public static function all($columns = ['*']): Collection
    {
        $response = app(ApiPaymentService::class)->search([]);
        return app(ApiPaymentBuilder::class)->fetchMany($response);
    }

    public static function find(string $uuid): ?ApiPaymentModel
    {
        if (!Str::isUuid($uuid)) {
            return null;
        }

        $response = app(ApiPaymentService::class)->byId($uuid);
        return app(ApiPaymentBuilder::class)->fetchOne($response);
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

    public static function where($column, $operator = null, $value = null, $boolean = 'and'): ApiPaymentBuilder
    {
        // Ignoramos $operator y $boolean, mantenemos compatibilidad con Eloquent
        return app(ApiPaymentBuilder::class)->where($column, $operator ?? $value);
    }

    public static function whereIn(string $column, array $values): ApiPaymentBuilder
    {
        return app(ApiPaymentBuilder::class)->whereIn($column, $values);
    }

    public static function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return app(ApiPaymentBuilder::class)->paginate($perPage);
    }

    public static function get(): Collection
    {
        return app(ApiPaymentBuilder::class)->get();
    }

    public function setClient(mixed $client): void
    {
        if (is_array($client)) {
            $this->client = ClientData::from($client);
        }
    }

    public function setPaymentGateway(mixed $payment_gateway): void
    {
        if (is_array($payment_gateway)) {
            $this->paymentGateway = PaymentGatewayData::from($payment_gateway);
        }
    }

    private function setPaymentCard(mixed $payment_card): void
    {
        if (is_array($payment_card)) {
            $this->paymentCard = PaymentCardData::from($payment_card);
        }
    }

    private function setPaymentIntention(mixed $payment_intention): void
    {
        if (is_array($payment_intention)) {
            $this->paymentIntention = PaymentIntentionData::from($payment_intention);
        }
    }

    private function setPaymentDetail(mixed $payment_detail): void
    {
        if (is_array($payment_detail)) {
            $this->paymentDetail = PaymentDetailData::from($payment_detail);
        }
    }

    private function setPayer(mixed $payer): void
    {
        if (is_array($payer)) {
            $this->payer = PayerData::from($payer);
        }
    }

    private function setItems(mixed $items): void
    {
        if (is_array($items)) {
            $this->items = collect($items)
                ->map(fn($item) => ItemData::from($item))
                ->toArray();
        }
    }
}
