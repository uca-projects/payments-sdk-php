<?php

namespace App\Data;

use App\Services\PaymentService;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

/**
 * @param string $adapter
 * @param string $api_key
 * @param string $external_reference
 * @param string|null $client_domain
 * @param string|null $token
 * @param string|null $last_digits
 * @param int|null $payment_method_id
 * @param string|null $bin
 * @param string|null $currency
 * @param float|null $amount
 * @param int|null $installments
 * @param string|null $notification_url
 * @param array<string,mixed>|null $back_urls
 * @param \App\Data\ItemData[] $items
 * @param \App\Data\PayerData $payer
 * @param \App\Data\HolderData|null $holder
 */
#[OA\Schema(
    title: 'PaymentCreateRequestData',
    description: 'Data for create an intention payment',
    required: ["adapter", "api_key", "external_reference", "items", "payer"],
    properties: [
        new OA\Property(property: 'adapter', ref: '#/components/schemas/AdapterEnum'),
        new OA\Property(property: 'api_key', type: 'string', example: 'your-api-key'),
        new OA\Property(property: 'external_reference', type: 'string', example: 'your-external-reference'),
        new OA\Property(property: 'token', type: 'string', example: '{{tokenVisa}}', description: 'Token de seguridad asociado al pago'),
        new OA\Property(property: 'last_digits', type: 'string', example: '7787', description: 'Últimos cuatro dígitos de la tarjeta'),
        new OA\Property(property: 'holder_name', type: 'string', example: 'Pedro', description: 'Nombre completo del titular.'),
        new OA\Property(property: 'holder_surname', type: 'string', example: 'Lopez', description: 'Apellido completo del titular'),
        new OA\Property(property: 'holder_doc_type', type: 'string', example: 'DNI', description: 'Tipo de identificación'),
        new OA\Property(property: 'holder_doc_number', type: 'string', example: '11222333', description: 'Número de identificación'),
        new OA\Property(property: 'payment_method_id', type: 'integer', example: 1, description: 'Identificador del método de pago utilizado.'),
        new OA\Property(property: 'bin', type: 'string', example: '1', description: 'Primeros dígitos de la tarjeta (Bank Identification Number).'),
        new OA\Property(property: 'installments', type: 'integer', example: 1),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
        new OA\Property(property: 'holder', ref: '#/components/schemas/HolderData'),
        new OA\Property(property: 'notification_url', type: 'string', example: 'https://google.com'),
        new OA\Property(
            property: 'back_urls',
            type: 'object',
            description: "Return URLs for each transaction status.",
            properties: [
                new OA\Property(
                    property: "success",
                    type: "string",
                    example: "https://yourdomain.com/payments/success"
                ),
                new OA\Property(
                    property: "pending",
                    type: "string",
                    example: "https://yourdomain.com/payments/pending"
                ),
                new OA\Property(
                    property: "failure",
                    type: "string",
                    example: "https://yourdomain.com/payments/failure"
                ),
                new OA\Property(
                    property: "unique",
                    type: "string",
                    example: "https://yourdomain.com/payments/unique"
                )
            ]
        ),
    ]
)]
class PaymentCreateRequestData extends Data
{
    public function __construct(
        public string $adapter,
        public string $api_key,
        public string $external_reference,
        public ?string $client_domain,
        public ?string $token,
        public ?string $last_digits,
        public ?int $payment_method_id,
        public ?string $bin,
        public ?string $currency,
        public ?float $amount,
        public ?int $installments,
        public ?string $notification_url,
        public ?array $back_urls,
        public array $items,
        public PayerData $payer,
        public ?HolderData $holder,
    ) {
        $client_domain = rtrim(request()->headers->get('origin'), '/');
        $this->api_key = Str::after(str_replace(' ', '+', request()->api_key), 'base64:');
        $this->client_domain = empty($client_domain) ? null : $client_domain;
        $this->currency = 'ARS';
        $this->amount = PaymentService::getAmount($items ?? []);;
    }
}
