<?php

namespace Uca\Payments\Services\Payments;

use Spatie\LaravelData\DataCollection;
use Uca\Payments\Clients\ApiRemotePaymentsClient;
use Uca\Payments\Data\PaginationData;
use Uca\Payments\Data\Payment\PaymentCollectionData;
use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Remote\GetPaymentData;
use Uca\Payments\Data\Requests\Remote\SearchPaymentsData;
use Uca\Payments\Exceptions\ApiClientException;

class ApiRemotePaymentService
{
    public function __construct(
        private ApiRemotePaymentsClient $apiRemotePaymentsClient
    ) {}

    public function search(SearchPaymentsData $searchPaymentsData): PaymentCollectionData
    {
        try {
            $response = $this->apiRemotePaymentsClient->search($searchPaymentsData);
            return new PaymentCollectionData(
                items: PaymentData::collect($response['data'], DataCollection::class),
                pagination: PaginationData::from($response['pagination']),
            );
        } catch (ApiClientException $e) {
            throw $e;
        }
    }

    public function getPayment(GetPaymentData $getPaymentData): ?PaymentData
    {
        try {
            $response = $this->apiRemotePaymentsClient->getPayment($getPaymentData);
            return empty($response['data']) ? null : PaymentData::from($response['data']);
        } catch (ApiClientException $e) {
            throw $e;
        }
    }
}
