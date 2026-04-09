<?php

namespace Uca\Payments\Services\Payments;

use Spatie\LaravelData\DataCollection;
use Uca\Payments\Clients\ApiLocalPaymentsClient;
use Uca\Payments\Data\PaginationData;
use Uca\Payments\Data\Payment\PaymentCollectionData;
use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;
use Uca\Payments\Exceptions\ApiClientException;

class ApiLocalPaymentService
{
    public function __construct(private ApiLocalPaymentsClient $apiLocalPaymentsClient) {}

    public function search(SearchPaymentsData $searchPaymentsData): PaymentCollectionData
    {
        try {
            $response = $this->apiLocalPaymentsClient->search($searchPaymentsData);

            return new PaymentCollectionData(
                items: PaymentData::collect($response['data'], DataCollection::class),
                pagination: PaginationData::from($response['pagination']),
            );
        } catch (ApiClientException $e) {
            throw $e;
        }
    }

    public function getPayment(GetPaymentData $getPaymentData): PaymentData
    {
        try {
            $response = $this->apiLocalPaymentsClient->getPayment($getPaymentData);
            return PaymentData::from($response['data']);
        } catch (ApiClientException $e) {
            throw $e;
        }
    }
}
