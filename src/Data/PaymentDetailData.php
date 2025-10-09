<?php

namespace Uca\PaymentsSharedClass\Data;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'PaymentDetailData',
    title: 'PaymentDetailData',
    description: 'Payment detail attributes',
    properties: [
        new OA\Property(property: 'token_card', type: 'string', nullable: true),
        new OA\Property(property: 'ticket', type: 'string', nullable: true),
        new OA\Property(property: 'card_authorization_code', type: 'string', nullable: true),
        new OA\Property(property: 'installments', type: 'integer', nullable: true),
        new OA\Property(property: 'error', type: 'string', nullable: true),
    ]
)]
class PaymentDetailData extends Data
{
    /**
     * @param string|null $token_card
     * @param string|null $ticket
     * @param string|null $card_authorization_code
     * @param int|null $installments
     * @param string|null $error
     */
    public function __construct(
        public ?string $token_card,
        public ?string $ticket,
        public ?string $card_authorization_code,
        public ?int $installments,
        public ?string $error,
    ) {}
}
