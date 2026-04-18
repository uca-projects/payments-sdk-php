<?php

namespace Uca\Payments\Data\Payment;

use App\Models\PaymentGateway;
use Spatie\LaravelData\Optional;
use Uca\Payments\Enums\GatewayEnum;
use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

/* #[OA\Schema(
    schema: 'PaymentGatewayData',
    title: 'PaymentGatewayData',
    description: 'Payment Gateway attributes',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'Id del gateway de pago'),
        new OA\Property(property: 'alias', type: 'string', description: 'Alias del gateway de pago'),
        new OA\Property(property: 'name', ref: '#/components/schemas/GatewayEnum'),
        new OA\Property(property: 'description', type: 'string', description: 'DescripciÃ³n del gateway de pago'),
        new OA\Property(property: 'logo_url', type: 'string', description: 'Logo del gateway de pago'),
        new OA\Property(property: 'credential', type: 'object', description: 'Credenciales del gateway de pago'),
    ],
    required: ['id', 'name', 'credential']
)] */

class PaymentGatewayData extends Data
{
    public function __construct(
        public string $id,
        public ?string $alias,
        public GatewayEnum $name,
        public string|Optional|null $description = null,
        public string|Optional|null $logo_url = null,
        public array|Optional|null $credential = null,
    ) {
        $this->description ??= Optional::create();
        $this->logo_url ??= Optional::create();
        $this->credential ??= Optional::create();
    }

    public function toModel(): PaymentGateway
    {
        $attributes = array_filter(
            $this->toArray(),
            fn($value) => !is_null($value) && !($value instanceof Optional)
        );

        return new PaymentGateway($attributes);
    }
}
