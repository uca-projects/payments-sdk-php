<?php

namespace Uca\Payments\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Uca\Payments\Data\ClientData;
use Uca\Payments\Data\ItemData;
use Uca\Payments\Data\PayerData;
use Uca\Payments\Data\PaymentCardData;
use Uca\Payments\Data\PaymentGatewayData;
use Uca\Payments\Data\PaymentIntentionData;

class PaymentData extends Data
{
    public function __construct(
        public string $id,
        public string $client_id,
        public string $preference_id,
        public string $payment_gateway_id,
        public string $external_reference,
        public ?string $gateway_transaction_id,
        public ?string $client_domain,
        public float $amount,
        public string $currency,
        public string $status,
        public mixed $created_at,
        public mixed $updated_at,
        public ?PaymentCardData $payment_card,
        public ?PaymentIntentionData $payment_intention,
        public ?PayerData $payer,
        #[DataCollectionOf(ItemData::class)]
        public ?array $items,
        public ?ClientData $client,
        public ?PaymentGatewayData $payment_gateway
    ) {
        // Normalize values
        $this->client_domain = $client_domain ? strtolower($client_domain) : null;
        $this->currency = strtoupper($currency);
        $this->status = strtoupper($status);

        //Detectar y convertir fechas dinámicamente
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
}
