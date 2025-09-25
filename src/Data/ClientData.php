<?php

namespace Uca\PaymentsSharedClass\Data;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'ClientData',
    title: 'ClientData',
    description: 'Client attributes',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', description: 'Id del cliente'),
        new OA\Property(property: 'name', type: 'string', description: 'Nombre del cliente'),
        new OA\Property(property: 'domain', type: 'string', description: 'Dominio del cliente'),
    ],
    required: ['id', 'name', 'domain']
)]
class ClientData extends Data
{
    /**
     * @param string $id
     * @param string $name
     * @param string $domain
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $domain
    ) {}
}
