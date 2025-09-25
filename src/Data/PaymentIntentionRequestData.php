<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentIntentionRequestData',
    title: 'PaymentIntentionRequestData',
    description: 'Payment intention request attributes',
    properties: [
        new OA\Property(property: 'external_reference', type: 'string', description: 'Referencia externa'),
        new OA\Property(property: 'preference_id', type: 'string', format: 'uuid', description: 'Id de la preferencia'),
        new OA\Property(property: 'client_id', type: 'string', format: 'uuid', description: 'Id del cliente'),
        new OA\Property(property: 'client_domain', type: 'string', description: 'Dominio del cliente'),
        new OA\Property(property: 'token_card', type: 'object', description: 'Token de la tarjeta'),
        new OA\Property(property: 'currency', type: 'string', description: 'Moneda'),
        new OA\Property(property: 'installments', type: 'integer', description: 'Cuotas'),
        new OA\Property(property: 'back_urls', type: 'object', description: 'URLs de retorno'),
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/ItemData')),
        new OA\Property(property: 'payer', ref: '#/components/schemas/PayerData'),
        new OA\Property(property: 'payment_gateway', ref: '#/components/schemas/PaymentGatewayData'),
    ],
    required: ['external_reference', 'preference_id', 'client_id', 'client_domain', 'items', 'payer', 'payment_gateway']
)]
class PaymentIntentionRequestData extends Data
{
    public function __construct(
        public string             $external_reference,
        public string             $preference_id,
        public string             $client_id,
        public string             $client_domain,
        public ?array             $token_card,
        public ?string            $currency,
        public ?int               $installments,
        public ?array             $back_urls,
        public array              $items,
        public PayerData          $payer,
        public PaymentGatewayData $payment_gateway,
    ) {
        $this->currency = 'ARS';
        $this->installments = 1;
    }

    public function getAmount(): float
    {
        return collect($this->items)->sum(fn($item) => $item->quantity * $item->unit_price);
    }
}
