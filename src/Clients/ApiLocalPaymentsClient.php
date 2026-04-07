<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;

class ApiLocalPaymentsClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'search' => '/api/payments/local/search?sort={sort}&criteria={criteria}&external_reference={external_reference}&range={range}&begin_date={begin_date}&end_date={end_date}&store_id={store_id}&pos_id={pos_id}&collector.id={collector_id}&payer.id={payer_id}&offset={offset}&limit={limit}',
        'getPayment' => '/api/payments/local/{id}',
        'sync' => '/api/payments/local/{uniqueField}/{value}/sync',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    final public function getPayment(GetPaymentData $getPaymentRequestData): ?PaymentData
    {
        $response = $this->doGet(
            self::ENDPOINTS['getPayment'],
            [
                'id' => $getPaymentRequestData->id
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

    public function sync(string $unique_field, string $value): array
    {
        $url_params = [
            'uniqueField' => $unique_field,
            'value' => $value
        ];
        return $this->doPut(self::ENDPOINTS['sync'], $url_params);
    }
}
