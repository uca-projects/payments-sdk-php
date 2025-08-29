<?php

namespace Uca\PaymentsSharedClass\Enums;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentStatusEnum',
    title: 'PaymentStatusEnum',
    type: 'string',
    enum: ['CREATED', 'APPROVED', 'PENDING', 'REJECTED', 'EXPIRED', 'CANCELED', 'OTHER']
)]
enum PaymentStatusEnum: string
{
    case CREATED = 'CREATED';
    case APPROVED = 'APPROVED';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case EXPIRED = 'EXPIRED';
    case CANCELED = 'CANCELED';
    case OTHER = 'OTHER';
}
