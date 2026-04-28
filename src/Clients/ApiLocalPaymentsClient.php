<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;

class ApiLocalPaymentsClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'search' => '/api/local/payment/search?external_reference_like={external_reference_like}&preference_id={preference_id}&client_id={client_id}&payment_gateway_id={payment_gateway_id}&gateway_transaction_id={gateway_transaction_id}&status={status}&min_amount={min_amount}&max_amount={max_amount}&min_created_at={min_created_at}&max_created_at={max_created_at}&offset={offset}&limit={limit}',
        'getPayment' => '/api/local/payment/{id}',
        'sync' => '/api/local/payment/{id}/sync',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getPayment(GetPaymentData $getPaymentData): array
    {
        $response = $this->doGet(
            self::ENDPOINTS['getPayment'],
            [
                'id' => $getPaymentData->id
            ]
        );

        return $response;
    }

    public function search(SearchPaymentsData $searchData): array
    {
        return $this->doGet(self::ENDPOINTS['search'], $searchData->toArray());
    }

    public function sync(GetPaymentData $getPaymentData): array
    {
        $url_params = [
            'id' => $getPaymentData->id
        ];

        return $this->doPut(self::ENDPOINTS['sync'], $url_params);
    }
}
