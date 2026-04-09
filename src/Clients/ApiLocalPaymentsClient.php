<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;

class ApiLocalPaymentsClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'search' => '/api/local/payment/search?sort={sort}&criteria={criteria}&external_reference={external_reference}&range={range}&begin_date={begin_date}&end_date={end_date}&store_id={store_id}&pos_id={pos_id}&collector.id={collector_id}&payer.id={payer_id}&offset={offset}&limit={limit}',
        'getPayment' => '/api/local/payment/{id}',
        'sync' => '/api/local/payment/{id}/sync',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    final public function getPayment(GetPaymentData $getPaymentRequestData): array
    {
        $response = $this->doGet(
            self::ENDPOINTS['getPayment'],
            [
                'id' => $getPaymentRequestData->id
            ]
        );

        return $response;
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
