<?php

namespace Uca\Payments\Enums;

use OpenApi\Attributes as OA;

/* #[OA\Schema(
    schema: 'GatewayEnum',
    title: 'GatewayEnum',
    type: 'string',
    enum: ['modo', 'getnet', 'payway', 'mercadopago', 'macroclickdepago']
)] */

enum GatewayEnum: string
{
    case MODO = 'modo';
    case GETNET = 'getnet';
    case PAYWAY = 'payway';
    case MERCADOPAGO = 'mercadopago';
    case MACROCLICKDEPAGO = 'macroclickdepago';
}
