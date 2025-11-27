<?php

namespace Uca\Payments\Data;

use Spatie\LaravelData\Data;
use Uca\Payments\Enums\PaymentGatewayEnum;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentGatewayData',
    title: 'PaymentGatewayData',
    description: 'Payment Gateway attributes',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'Id del gateway de pago'),
        new OA\Property(property: 'alias', type: 'string', description: 'Alias del gateway de pago'),
        new OA\Property(property: 'name', ref: '#/components/schemas/PaymentGatewayEnum'),
        new OA\Property(property: 'description', type: 'string', description: 'DescripciÃ³n del gateway de pago'),
        new OA\Property(property: 'logo_url', type: 'string', description: 'Logo del gateway de pago'),
        new OA\Property(property: 'credential', type: 'object', description: 'Credenciales del gateway de pago'),
    ],
    required: ['id', 'name', 'credential']
)]
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
        public string             $id,
        public ?string            $alias,
        public PaymentGatewayEnum $name,
        public ?string            $description,
        public ?string            $logo_url,
        public ?array             $credential,
    ) {}
}
