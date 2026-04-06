<?php

namespace Uca\Payments\Data\Requests;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

/**
 * @param  string  $payment_gateway_id
 * @param  string  $unique_field
 * @param  string  $value
 */
class GetPaymentData extends Data
{
    public function __construct(
        #[Rule(['uuid', 'exists:payment_gateways,id'])]
        public string $payment_gateway_id,

        #[Rule('in:gateway_transaction_id,external_reference')]
        public string $unique_field,

        public string $value,
    ) {}
}
