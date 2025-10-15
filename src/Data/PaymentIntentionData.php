<?php

namespace Uca\Payments\Data;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'PaymentIntentionData',
    title: 'PaymentIntentionData',
    description: 'Payment intention attributes',
    properties: [
        new OA\Property(property: 'transaction_intent_id', type: 'string', nullable: true),
        new OA\Property(property: 'checkout_url', type: 'string', nullable: true),
        new OA\Property(property: 'qr', type: 'string', nullable: true),
        new OA\Property(property: 'deep_link', type: 'string', nullable: true),
        new OA\Property(property: 'notification_url', type: 'string', nullable: true),
        new OA\Property(property: 'back_urls', type: 'object', nullable: true),
    ]
)]
class PaymentIntentionData extends Data
{
    /**
     * @param string|null $transaction_intent_id
     * @param string|null $checkout_url
     * @param string|null $qr
     * @param string|null $deep_link
     * @param string|null $notification_url
     * @param array|null $back_urls
     */
    public function __construct(
        public ?string $transaction_intent_id,
        public ?string $checkout_url,
        public ?string $qr,
        public ?string $deep_link,
        public ?string $notification_url,
        public ?array $back_urls,
    ) {}
}
