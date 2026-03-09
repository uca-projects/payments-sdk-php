<?php

namespace Uca\Payments\Services;

use Uca\Payments\Data\PaymentGatewayData;

class ApiLocalPaymentService extends ApiPaymentService
{
    private const ENDPOINTS = [
        'payment_gateway' => 'payment-gateway',
        'search' => 'payment/local/search?',
        'unique_field' => 'payment/local/{uniqueField}/{value}',
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

    public function byExternalReference(string $external_reference): array
    {
        $params = [
            'uniqueField' => 'external_reference',
            'value' => $external_reference,
        ];
        return $this->doGet(self::ENDPOINTS['unique_field'], $params);
    }

    public function byTransactionId(string $transaction_id): array
    {
        $params = [
            'uniqueField' => 'gateway_transaction_id',
            'value' => $transaction_id
        ];
        return $this->doGet(self::ENDPOINTS['unique_field'], $params);
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
