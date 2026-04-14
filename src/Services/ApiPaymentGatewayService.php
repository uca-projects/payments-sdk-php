<?php

namespace Uca\Payments\Services;

use Uca\Payments\Clients\ApiPaymentGatewayClient;
use Uca\Payments\Data\Payment\PaymentGatewayData;
use Uca\Payments\Exceptions\ApiClientException;

class ApiPaymentGatewayService
{
    public function __construct(
        private ApiPaymentGatewayClient $apiPaymentGatewayClient,
    ) {}

    public function upsert(PaymentGatewayData $paymentGatewayData): PaymentGatewayData
    {
        try {
            $paymentGateway = $this->apiPaymentGatewayClient->upsert($paymentGatewayData);
            return PaymentGatewayData::from($paymentGateway['data']);
        } catch (ApiClientException $e) {
            throw $e;
        }
    }
}
