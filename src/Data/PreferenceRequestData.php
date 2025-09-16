<?php

namespace Uca\PaymentsSharedClass\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'PreferenceRequestData',
    description: 'Data for create a preference',
    required: ["client_id", "items", "payer", "back_url", "expires_at"],
    properties: [
        new OA\Property(property: 'client_id', type: 'string', example: 'your-client_id uuid'),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
        new OA\Property(property: 'back_url', type: 'string', example: 'https://yourdomain.com/payments/success'),
        new OA\Property(
            property: 'expires_at',
            type: 'string',
            format: 'date-time',
            example: '2025-09-03T14:30:00Z',
            description: 'Datetime when the preference expires. Defaults to 10 minutes from now if omitted.'
        ),
    ],
)]
/**
 * @param string $client_id
 * @param ItemData[] $items
 * @param PayerData $payer
 * @param string $back_url
 * @param string $expires_at
 */
class PreferenceRequestData extends Data
{
    public function __construct(
        public string $client_id,
        /** @var ItemData[] */
        public array $items,
        public PayerData $payer,
        public string $back_url,
        public ?string $expires_at = null,
    ) {
        $this->expires_at = $expires_at ?? Carbon::now()->addMinutes(10)->toISOString();
    }
}
