<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PreferenceResponseData',
    title: 'PreferenceResponseData',
    description: 'Payment intention response attributes',
    properties: [
        new OA\Property(property: 'preference_id', type: 'string', format: 'uuid', description: 'Id de la preferencia'),
        new OA\Property(property: 'client', ref: '#/components/schemas/ClientData'),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float', description: 'Monto total'),
        new OA\Property(property: 'back_url', type: 'string', description: 'URL de retorno'),
        new OA\Property(property: 'expires_at', type: 'string', format: 'date-time', description: 'Fecha de expiraciÃ³n'),
        new OA\Property(property: 'checkout_url', type: 'string', description: 'URL de checkout'),
        new OA\Property(property: 'payment_gateways', type: 'array', items: new OA\Items(ref: '#/components/schemas/PaymentGatewayData')),
    ],
    required: ['preference_id', 'client', 'items', 'payer', 'total_amount', 'back_url', 'expires_at', 'checkout_url', 'payment_gateways']
)]
class PreferenceResponseData extends Data
{
    public function __construct(
        public string $preference_id,
        public ClientData $client,
        public array $items,
        public PayerData $payer,
        public float $total_amount,
        public string $back_url,
        public string $expires_at,
        public string $checkout_url,
        public array $payment_gateways,
    ) {}
}
