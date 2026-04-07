<?php

namespace Uca\Payments\Data\Requests\Local;

use Uca\Payments\Enums\PaymentStatusEnum;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

/**
 * @param  string|null  $external_reference_like
 * @param  string|null  $preference_id
 * @param  string|null  $client_id
 * @param  string|null  $payment_gateway_id
 * @param  string|null  $gateway_transaction_id
 * @param  PaymentStatusEnum|null  $status
 * @param  float|null  $min_amount
 * @param  float|null  $max_amount
 * @param  string|null  $min_created_at
 * @param  string|null  $max_created_at
 * @param  int|null  $offset
 * @param  int|null  $limit
 */
class SearchPaymentsData extends Data
{
    public function __construct(
        #[Rule(['string', 'max:255'])]
        public ?string $external_reference_like = null,

        #[Rule(['uuid', 'exists:preferences,id'])]
        public ?string $preference_id = null,

        #[Rule(['uuid', 'exists:clients,id'])]
        public ?string $client_id = null,

        #[Rule(['uuid', 'exists:payment_gateways,id'])]
        public ?string $payment_gateway_id = null,

        #[Rule(['string', 'max:255'])]
        public ?string $gateway_transaction_id = null,

        #[Rule([new Enum(PaymentStatusEnum::class)])]
        public ?PaymentStatusEnum $status = null,

        #[Rule(['numeric', 'min:0'])]
        public ?float $min_amount = null,

        #[Rule(['numeric', 'min:0', 'gte:min_amount'])]
        public ?float $max_amount = null,

        #[Rule('date')]
        public ?string $min_created_at = null,

        #[Rule(['date', 'after_or_equal:min_created_at'])]
        public ?string $max_created_at = null,

        #[Rule(['integer', 'min:0'])]
        public ?int $offset = null,

        #[Rule(['integer', 'min:1', 'max:100'])]
        public ?int $limit = null,
    ) {}
}
