<?php

namespace Uca\Payments\Data\Payment;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;
use Uca\Payments\Traits\DataModelTrait;

#[OA\Schema(
    schema: 'PaymentDetailData',
    title: 'PaymentDetailData',
    description: 'Payment detail attributes',
    properties: [
        new OA\Property(property: 'token_card', type: 'object', nullable: true),
        new OA\Property(property: 'ticket', type: 'string', nullable: true),
        new OA\Property(property: 'card_authorization_code', type: 'string', nullable: true),
        new OA\Property(property: 'installments', type: 'integer', nullable: true),
        new OA\Property(property: 'error', type: 'object', nullable: true),
    ]
)]
class PaymentDetailData extends Data
{
    use DataModelTrait;
    /**
     * @param array|null $token_card
     * @param string|null $ticket
     * @param string|null $card_authorization_code
     * @param int|null $installments
     * @param string|null $error
     */
    public function __construct(
        public ?int $id,
        public ?string $payment_id,
        public ?array $token_card,
        public ?string $ticket,
        public ?string $card_authorization_code,
        public ?int $installments,
        public ?array $error,
    ) {}
}
