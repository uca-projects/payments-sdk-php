<?php

namespace Uca\Payments\Data\Requests\Remote;

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
        #[Rule(['uuid', 'required', 'exists:payment_gateways,id'])]
        public string $payment_gateway_id,

        #[Rule(['in:gateway_transaction_id,external_reference', 'required'])]
        public string $unique_field,

        #[Rule(['string', 'required'])]
        public string $value,
    ) {}
}
