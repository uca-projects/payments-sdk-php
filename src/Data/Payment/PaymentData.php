<?php

namespace Uca\Payments\Data\Payment;

use App\Models\Item;
use App\Models\Payer;
use App\Models\Payment;
use App\Models\PaymentCard;
use App\Models\PaymentDetail;
use App\Models\PaymentGateway;
use App\Models\PaymentIntention;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Uca\Payments\Enums\PaymentStatusEnum;

class PaymentData extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $client_id,
        public ?string $preference_id,
        public ?string $payment_gateway_id,
        public string $external_reference,
        public ?string $gateway_transaction_id,
        public ?string $client_domain,
        public float $amount,
        public string $currency,
        public PaymentStatusEnum $status,
        public mixed $created_at,
        public mixed $updated_at,
        public ?PaymentCardData $paymentCard,
        public ?PaymentIntentionData $paymentIntention,
        public ?PaymentDetailData $paymentDetail,
        public ?PaymentGatewayData $paymentGateway,
        public ?PayerData $payer,
        #[DataCollectionOf(ItemData::class)]
        public ?array $items,
        public ?ClientData $client
    ) {
        // Normalize values
        $this->client_domain = $client_domain ? strtolower($client_domain) : null;
        $this->currency = strtoupper($currency);

        // Detectar y convertir fechas dinámicamente
        $this->created_at = $this->parseDate($created_at);
        $this->updated_at = $this->parseDate($updated_at);
    }

    private function parseDate($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        // Ya es un Carbon
        if ($value instanceof Carbon) {
            return $value;
        }

        // Detectar formato ISO (contiene "T" o zona horaria)
        if (preg_match('/T\d{2}:\d{2}:\d{2}/', $value)) {
            return Carbon::parse($value);
        }

        // Detectar formato MySQL
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value);
        }

        // Intentar parsear genéricamente
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function toModel(): Payment
    {
        $attributes = array_filter($this->toArray(), fn($value) => !is_null($value));

        $payment = new Payment($attributes);

        if ($this->id) {
            $payment->id = $this->id;
            $payment->exists = true;
        }

        $relations = [
            'paymentCard'      => PaymentCard::class,
            'paymentIntention' => PaymentIntention::class,
            'paymentDetail'    => PaymentDetail::class,
            'paymentGateway'   => PaymentGateway::class,
            'payer'            => Payer::class,
        ];

        foreach ($relations as $relation => $modelClass) {
            if ($this->{$relation}) {
                $payment->setRelation($relation, $this->{$relation}->toModel());
            }
        }

        if ($this->items) {
            $items = collect($this->items)->map(fn($itemData) => $itemData->toModel());
            $payment->setRelation('items', $items);
        }

        return $payment;
    }
}
