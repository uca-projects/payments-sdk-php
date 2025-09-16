<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'PreferenceResponseData',
    description: 'Data for create a preference',
    required: ["client_id", "items", "payer", "total_amount", "back_url"],
    properties: [
        new OA\Property(property: 'client_id', type: 'string', example: 'your-client_id uuid'),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float', example: 10.5, description: 'Sum items unit_price * quantity'),
        new OA\Property(
            property: 'expires_at',
            type: 'string',
            format: 'date-time',
            example: '2025-09-03T14:30:00Z',
            description: 'Datetime when the preference expires. Defaults to 10 minutes from now if omitted.'
        ),
        new OA\Property(property: 'back_url', type: 'string', example: 'https://yourdomain.com/payments/success'),
    ]
)]

/**
 * @param string $preference_id
 * @param string $client_id
 * @param ItemData[] $items
 * @param PayerData $payer
 * @param float $total_amount
 * @param string $back_url
 * @param string $expires_at
 * @param string $checkout_url
 * @param array $payment_gateways
 */


/**
 * @param array<int, ItemData> $items
 */
class PreferenceResponseData extends Data
{
    public function __construct(
        public string $preference_id,
        public string $client_id,
        public array $items,
        public PayerData $payer,
        public float $total_amount,
        public string $back_url,
        public string $expires_at,
        public string $checkout_url,
        public array $payment_gateways,
    ) {}
}
