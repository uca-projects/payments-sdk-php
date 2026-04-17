<?php

namespace Uca\Payments\Data\Payment;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'PaymentCardData',
    title: 'PaymentCardData',
    description: 'Payment card attributes',
    properties: [
        new OA\Property(property: 'bank_name', type: 'string', nullable: true),
        new OA\Property(property: 'issuer_name', type: 'string', nullable: true),
        new OA\Property(property: 'bin', type: 'string', nullable: true),
        new OA\Property(property: 'last_digits', type: 'string', nullable: true),
        new OA\Property(property: 'card_type', type: 'string', nullable: true),
        new OA\Property(property: 'holder', type: 'object', nullable: true),
    ]
)]
class PaymentCardData extends Data
{
    public function __construct(
        public ?string $bank_name,
        public ?string $issuer_name,
        public ?string $bin,
        public ?string $last_digits,
        public ?string $card_type,
        public ?HolderData $holder,
    ) {}
}
