<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Remote\GetPaymentData;
use Uca\Payments\Data\Requests\Remote\SearchPaymentsData;

class ApiRemotePaymentsClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'search' => '/api/payments/remote/search?sort={sort}&criteria={criteria}&external_reference={external_reference}&range={range}&begin_date={begin_date}&end_date={end_date}&store_id={store_id}&pos_id={pos_id}&collector.id={collector_id}&payer.id={payer_id}&offset={offset}&limit={limit}',
        'getPayment' => '/api/payments/remote/{uniqueField}/{value}',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    final public function getPayment(GetPaymentData $getPaymentRequestData): ?PaymentData
    {
        if (!in_array($getPaymentRequestData->unique_field, ['gateway_transaction_id', 'external_reference'])) {
            throw new \Exception("Invalid payment unique field: {$getPaymentRequestData->unique_field}");
        }

        $response = $this->doGet(
            self::ENDPOINTS['getPayment'],
            [
                'uniqueField' => $getPaymentRequestData->unique_field,
                'value' => $getPaymentRequestData->value
            ]
        );

        // Payment not found
        if (empty($response)) {
            return null;
        }

        return PaymentData::from($response);
    }

    public function search(SearchPaymentsData $searchData): array
    {
        return $this->doGet(self::ENDPOINTS['search'], $searchData->toArray());
    }
}
