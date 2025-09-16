<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;

/**
 * @param string $external_reference
 * @param string $client_id
 * @param string $client_domain
 * @param array|null $token_card
 * @param string $currency
 * @param int $installments
 * @param array<string,mixed>|null $back_urls
 * @param ItemData[] $items
 * @param PayerData $payer
 * @param PaymentGatewayData $payment_gateway
 */

class PaymentIntentionRequestData extends Data
{
    public function __construct(
        public string $external_reference,
        public string $client_id,
        public string $client_domain,
        public ?array $token_card,
        public ?string $currency,
        public ?int $installments,
        public ?array $back_urls,
        /** @var ItemData[] */
        public array $items,
        public PayerData $payer,
        public PaymentGatewayData $payment_gateway,
    ) {
        $this->currency = 'ARS';
        $this->installments = 1;
    }
}
