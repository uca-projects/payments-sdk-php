<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Payment\PaymentGatewayData;

class ApiPaymentGatewayClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'upsertPaymentGateway' => '/api/payment-gateways/{id}',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function upsert(PaymentGatewayData $paymentGatewayData): array
    {
        $url_params = [
            'id' => $paymentGatewayData->id
        ];
        $body_params = $paymentGatewayData->toArray();
        return $this->doPut(self::ENDPOINTS['upsertPaymentGateway'], $url_params, $body_params);
    }
}
