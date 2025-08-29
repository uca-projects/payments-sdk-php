<?php

namespace Uca\PaymentsSharedClass\Enums;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdapterEnum',
    title: 'AdapterEnum',
    type: 'string',
    enum: ['modo', 'getnet', 'payway', 'mercadopago', 'macroclick', 'clickdepago']
)]
enum AdapterEnum: string
{
    case MODO = 'modo';
    case GETNET = 'getnet';
    case PAYWAY = 'payway';
    case MERCADOPAGO = 'mercadopago';
    case MACROCLICK = 'macroclick';
    case CLICKDEPAGO = 'clickdepago';
}
