<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'PreferenceResponseData',
    description: 'Data for create a preference',
    required: ["client_id", "external_reference", "items", "payer", "total_amount", "back_urls"],
    properties: [
        new OA\Property(property: 'client_id', type: 'string', example: 'your-client_id uuid'),
        new OA\Property(property: 'external_reference', type: 'string', example: 'your-external-reference'),
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
        new OA\Property(
            property: 'back_urls',
            type: 'object',
            description: "Return URLs for each transaction status.",
            properties: [
                new OA\Property(
                    property: "success",
                    type: "string",
                    example: "https://yourdomain.com/payments/success"
                ),
                new OA\Property(
                    property: "pending",
                    type: "string",
                    example: "https://yourdomain.com/payments/pending"
                ),
                new OA\Property(
                    property: "failure",
                    type: "string",
                    example: "https://yourdomain.com/payments/failure"
                ),
                new OA\Property(
                    property: "unique",
                    type: "string",
                    example: "https://yourdomain.com/payments/unique"
                )
            ]
        ),
    ]
)]

/**
 * @param string $preference_id
 * @param string $client_id
 * @param string $external_reference
 * @param ItemData[] $items
 * @param PayerData $payer
 * @param float $total_amount
 * @param array<string,mixed>|null $back_urls
 * @param string $expires_at
 * @param string $checkout_url
 * @param array $payment_gateways
 */
class PreferenceResponseData extends Data
{
    public function __construct(
        public string $preference_id,
        public string $client_id,
        public string $external_reference,
        /** @var ItemData[] */
        public array $items,
        public PayerData $payer,
        public float $total_amount,
        public array $back_urls,
        public string $expires_at,
        public string $checkout_url,
        public array $payment_gateways,
    ) {}
}
