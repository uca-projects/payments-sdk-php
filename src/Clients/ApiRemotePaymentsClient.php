<?php

namespace Uca\Payments\Clients;

use Uca\Payments\Data\Requests\Remote\GetPaymentData;
use Uca\Payments\Data\Requests\Remote\SearchPaymentsData;

class ApiRemotePaymentsClient extends AbstractApiClient
{
    private const ENDPOINTS = [
        'search' => '/api/remote/payment/gateway/{payment_gateway_id}/search?sort={sort}&criteria={criteria}&gateway_transaction_id={gateway_transaction_id}&external_reference={external_reference}&range={range}&begin_date={begin_date}&end_date={end_date}&store_id={store_id}&pos_id={pos_id}&collector.id={collector_id}&payer.id={payer_id}&offset={offset}&limit={limit}',
        'getPayment' => '/api/remote/payment/gateway/{payment_gateway_id}/{unique_field}/{value}',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    final public function getPayment(GetPaymentData $getPaymentRequestData): array
    {
        if (!in_array($getPaymentRequestData->unique_field, ['gateway_transaction_id', 'external_reference'])) {
            throw new \Exception("Invalid payment unique field: {$getPaymentRequestData->unique_field}");
        }

        $response = $this->doGet(
            self::ENDPOINTS['getPayment'],
            $getPaymentRequestData->toArray()
        );

        return $response;
    }

    public function search(SearchPaymentsData $searchData): array
    {
        return $this->doGet(
            self::ENDPOINTS['search'],
            $searchData->toArray()
        );
    }
}
