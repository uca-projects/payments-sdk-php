<?php

namespace Uca\Payments\Data;

use Carbon\Carbon;
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
        public ?Carbon $created_at,
        public ?Carbon $updated_at,
        public ?PaymentCardData $payment_card,
        public ?PaymentIntentionData $payment_intention,
        public ?PayerData $payer,
        #[DataCollectionOf(ItemData::class)]
        public ?array $items,
        public ?ClientData $client,
        public ?PaymentGatewayData $payment_gateway
    ) {}
}
