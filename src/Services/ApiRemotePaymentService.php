<?php

namespace Uca\Payments\Services;

class ApiRemotePaymentService extends ApiPaymentService
{
    private const ENDPOINTS = [
        'external_reference' => 'payment/remote/{paymentGatewayId}/external_reference/{externalReference}',
        'transaction_id' => 'payment/remote/{paymentGatewayId}/gateway_transaction_id/{transactionId}',
        'search' => 'payment/remote/{paymentGatewayId}/search?',
    ];

    public function __construct()
    {
        parent::__construct();
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

    public function search(array $params = []): array
    {
        if (!isset($params['payment_gateway_id'])) {
            throw new \InvalidArgumentException('The "payment_gateway_id" parameter is required.');
        }

        $endpoint = self::ENDPOINTS['search'] . $this->makeUrlFromBody($params);

        $params = array_merge($params, [
            'paymentGatewayId' => $params['payment_gateway_id'],
        ]);

        return $this->doGet($endpoint, $params);
    }
}
