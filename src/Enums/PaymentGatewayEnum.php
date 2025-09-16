<?php

namespace Uca\PaymentsSharedClass\Enums;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentGatewayEnum',
    title: 'PaymentGatewayEnum',
    type: 'string',
    enum: ['modo', 'getnet', 'payway', 'mercadopago', 'macroclickdepago']
)]
enum PaymentGatewayEnum: string
{
    case MODO = 'modo';
    case GETNET = 'getnet';
    case PAYWAY = 'payway';
    case MERCADOPAGO = 'mercadopago';
    case MACROCLICKDEPAGO = 'macroclickdepago';
}
