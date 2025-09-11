<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'HolderData',
    description: 'Holder data',
    required: ["name", "surname", "doc_type", "doc_number"],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John'),
        new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
        new OA\Property(property: 'doc_type', type: 'string', example: 'DNI'),
        new OA\Property(property: 'doc_number', type: 'string', example: '12345678'),
    ]
)]
class HolderData extends Data
{

    /**
     * @param string $name
     * @param string $surname
     * @param string|null $doc_type
     * @param string|null $doc_number
     */
    public function __construct(
        public string $name,
        public string $surname,
        public string $doc_type,
        public string $doc_number,
    ) {}
}
