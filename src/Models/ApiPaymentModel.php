<?php

namespace Uca\Payments\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Uca\Payments\Data\PaymentData;
use Uca\Payments\Builders\ApiPaymentBuilder;
use Uca\Payments\Data\ClientData;
use Uca\Payments\Data\ItemData;
use Uca\Payments\Data\PayerData;
use Uca\Payments\Data\PaymentCardData;
use Uca\Payments\Data\PaymentDetailData;
use Uca\Payments\Data\PaymentGatewayData;
use Uca\Payments\Data\PaymentIntentionData;
use Uca\Payments\Services\ApiPaymentService;
use ReflectionClass;
use ReflectionNamedType;
use Carbon\Carbon;

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

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        // Generar fillable automáticamente desde PaymentData
        $this->fillable = $this->getFillableFromPaymentData();

        // Completo con Model los atributos fillables
        parent::__construct($attributes);

        // Genero las relaciones
        $this->setPaymentCard($attributes['payment_card'] ?? null);
        $this->setPaymentIntention($attributes['payment_intention'] ?? null);
        $this->setPaymentDetail($attributes['payment_detail'] ?? null);
        $this->setPayer($attributes['payer'] ?? null);
        $this->setItems($attributes['items'] ?? null);
        $this->setClient($attributes['client'] ?? null);
        $this->setPaymentGateway($attributes['payment_gateway'] ?? null);
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

    private function setClient(mixed $client): void
    {
        if (is_array($client)) {
            $this->client = ClientData::from($client);
        }
    }

    private function setPaymentGateway(mixed $payment_gateway): void
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

    /**
     * Obtiene las propiedades públicas del DTO PaymentData
     * y devuelve solo las que son de tipo escalar o Carbon.
     */
    private function getFillableFromPaymentData(): array
    {
        $reflection = new ReflectionClass(PaymentData::class);

        return collect($reflection->getProperties())
            ->filter(function ($property) {
                $type = $property->getType();

                // Si no hay tipo declarado, no la incluimos
                if (!$type instanceof ReflectionNamedType) {
                    return false;
                }

                $typeName = $type->getName();

                // Aceptar solo tipos escalares y Carbon, descarta los model relations
                return in_array($typeName, [
                    'string',
                    'int',
                    'float',
                    'bool',
                    Carbon::class,
                    '?' . Carbon::class, // por si es nullable
                ]);
            })
            ->map(fn($prop) => $prop->getName())
            ->values()
            ->toArray();
    }
}
