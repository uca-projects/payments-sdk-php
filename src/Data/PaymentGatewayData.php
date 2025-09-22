<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use Uca\PaymentsSharedClass\Enums\PaymentGatewayEnum;

class PaymentGatewayData extends Data
{
    /**
     * @param string $id
     * @param string $alias
     * @param PaymentGatewayEnum $name
     * @param string|null $description
     * @param string|null $logo_url
     * @param array $credential
     */
    public function __construct(
        public string $id,
        public ?string $alias,
        public PaymentGatewayEnum $name,
        public ?string $description,
        public ?string $logo_url,
        public ?array $credential,
    ) {}
}
