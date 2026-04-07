<?php

namespace Uca\Payments\Data\Requests\Local;

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
        #[Rule(['uuid', 'required'])]
        public string $id,
    ) {}
}
