<?php

namespace Uca\PaymentsSharedClass\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

/**
 * @param string $client_id
 * @param string $external_reference
 * @param \App\Data\ItemData[] $items
 * @param \App\Data\PayerData $payer
 * @param array<string,mixed>|null $back_urls
 * @param string $expires_at
 */
#[OA\Schema(
    title: 'PreferenceRequestData',
    description: 'Data for create a preference',
    required: ["client_id", "external_reference", "items", "payer", "back_urls", "expires_at"],
    properties: [
        new OA\Property(property: 'client_id', type: 'string', example: 'your-client_id uuid'),
        new OA\Property(property: 'external_reference', type: 'string', example: 'your-external-reference'),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
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
        new OA\Property(
            property: 'expires_at',
            type: 'string',
            format: 'date-time',
            example: '2025-09-03T14:30:00Z',
            description: 'Datetime when the preference expires. Defaults to 10 minutes from now if omitted.'
        ),
    ],
)]
class PreferenceRequestData extends Data
{
    public function __construct(
        public string $client_id,
        public string $external_reference,
        public array $items,
        public PayerData $payer,
        public array $back_urls,
        public ?string $expires_at = null,
    ) {
        $this->expires_at = $expires_at ?? Carbon::now()->addMinutes(10)->toISOString();
    }
}
