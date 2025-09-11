<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'PayerData',
    description: 'Payer data',
    required: ["payer_reference", "name", "surname", "email", "doc_type", "doc_number", "billing_address"],
    properties: [
        new OA\Property(property: 'payer_reference', type: 'string', example: '12345'),
        new OA\Property(property: 'name', type: 'string', example: 'John'),
        new OA\Property(property: 'surname', type: 'string', example: 'Doe'),
        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
        new OA\Property(property: 'doc_type', type: 'string', example: 'DNI'),
        new OA\Property(property: 'doc_number', type: 'string', example: '12345678'),
        new OA\Property(property: 'billing_address', type: 'object', properties: [
            new OA\Property(property: 'street_name', type: 'string', example: 'Fake Street'),
            new OA\Property(property: 'street_number', type: 'string', example: '123'),
            new OA\Property(property: 'zip_code', type: 'string', example: '1234'),
        ]),
    ]
)]
class PayerData extends Data
{

    /**
     * @param string|null $payer_reference
     * @param string $name
     * @param string $surname
     * @param string|null $email
     * @param string|null $doc_type
     * @param string|null $doc_number
     * @param array|null $billing_address
     */
    public function __construct(
        public ?string $payer_reference,
        public string $name,
        public string $surname,
        public ?string $email,
        public ?string $doc_type,
        public ?string $doc_number,
        public ?array $billing_address
    ) {}
}
