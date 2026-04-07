<?php

namespace Uca\Payments\Data\Requests\Remote;

use Uca\Payments\Data\Payment\ItemData;
use Uca\Payments\Data\Payment\PayerData;
use Spatie\LaravelData\Data;

/**
 * @param string $external_reference
 * @param string $preference_id
 * @param string $client_id
 * @param string $client_domain
 * @param array|null $token_card
 * @param string $currency
 * @param int $installments
 * @param array<string,mixed>|null $back_urls
 * @param ItemData[] $items
 * @param PayerData $payer
 */

/**
 * @param array<int, ItemData> $items
 */
class CreatePaymentData extends Data
{
    public function __construct(
        public string $external_reference,
        public string $preference_id,
        public string $client_id,
        public string $client_domain,
        public ?array $token_card,
        public ?string $currency,
        public ?int $installments,
        public ?array $back_urls,
        public array $items,
        public PayerData $payer,
    ) {
        $this->currency ??= 'ARS';
        $this->installments ??= 1;
    }

    public function getAmount(): float
    {
        return collect($this->items)->sum(fn($item) => $item->quantity * $item->unit_price);
    }
}
