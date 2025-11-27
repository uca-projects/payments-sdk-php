<?php

namespace Uca\Payments\Data;

use Spatie\LaravelData\Data;
use Uca\Payments\Enums\PaymentStatusEnum;

class PaymentSearchData extends Data
{
    public function __construct(
        public ?string $external_reference_like,
        public ?string $preference_id,
        public ?string $client_id,
        public ?string $payment_gateway_id,
        public ?PaymentStatusEnum $status,
        public ?float $min_amount,
        public ?float $max_amount,
        public ?string $min_created_at,
        public ?string $max_created_at,
    ) {}
}
