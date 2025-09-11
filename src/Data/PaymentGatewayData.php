<?php

namespace App\Data;

use App\Enums\PaymentGatewayEnum;
use Spatie\LaravelData\Data;

class PaymentGatewayData extends Data
{

    /**
     * @param string $alias
     * @param PaymentGatewayEnum $name
     * @param string|null $description
     * @param string|null $logo_url
     * @param array $credential
     */
    public function __construct(
        public string $alias,
        public PaymentGatewayEnum $name,
        public ?string $description,
        public ?string $logo_url,
        public array $credential,
    ) {}
}
