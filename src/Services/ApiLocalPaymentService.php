<?php

namespace Uca\Payments\Services;

use Uca\Payments\Data\PaymentGatewayData;

class ApiLocalPaymentService extends ApiPaymentService
{
    private const ENDPOINTS = [
        'search' => 'payment/local/search?',
        'payment_gateway' => 'payment-gateway',
        'payment_sync' => 'payment/{uniqueField}/{value}/sync',
    ];

    public function __construct()
    {
        parent::__construct();
    }
    public function sync(string $unique_field, string $value): array
    {
        $url_params = [
            'uniqueField' => $unique_field,
            'value' => $value
        ];
        return $this->doPut(self::ENDPOINTS['payment_sync'], $url_params);
    }

    public function byExternalReference(string $payment_gateway_id, string $external_reference): array
    {
        $params = [
            'externalReference' => $external_reference,
            'paymentGatewayId' => $payment_gateway_id
        ];
        return $this->doGet(self::ENDPOINTS['external_reference'], $params);
    }

    public function byTransactionId(string $payment_gateway_id, string $transaction_id): array
    {
        $params = [
            'transactionId' => $transaction_id,
            'paymentGatewayId' => $payment_gateway_id
        ];
        return $this->doGet(self::ENDPOINTS['transaction_id'], $params);
    }

    public function byPreferenceId(string $preference_id): array
    {
        $params = ['preference_id' => $preference_id];
        return $this->search($params);
    }

    public function byId(string $id): array
    {
        $params = ['id' => $id];
        return $this->search($params);
    }

    public function search(array $params = []): array
    {
        $endpoint = self::ENDPOINTS['search'] . $this->makeUrlFromBody($params);
        return $this->doGet($endpoint, $params);
    }

    public function updateOrCreatePaymentGateway(PaymentGatewayData $payment_gateway): array
    {
        $response = $this->doPost(self::ENDPOINTS['payment_gateway'], $payment_gateway->toArray());
        return  $response['data'];
    }
}
