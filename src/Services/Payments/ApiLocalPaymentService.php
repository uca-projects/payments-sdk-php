<?php

namespace Uca\Payments\Services\Payments;

use Uca\Payments\Clients\ApiLocalPaymentsClient;
use Uca\Payments\Data\PaginationData;
use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;
use Uca\Payments\Data\Response\ApiCollectionResponseData;
use Uca\Payments\Data\Response\ApiResponseData;
use Uca\Payments\Exceptions\ApiClientException;

class ApiLocalPaymentService
{
    public function __construct(private ApiLocalPaymentsClient $apiClient) {}

    public function search(SearchPaymentsData $searchPaymentsData): ApiCollectionResponseData
    {
        try {
            $response = $this->apiClient->search($searchPaymentsData);

            return new ApiCollectionResponseData(
                data: PaymentData::collect($response['data']),
                pagination: PaginationData::from($response['pagination']),
                meta: $response['meta'] ?? null
            );
        } catch (ApiClientException $e) {
            throw $e;
        }
    }

    public function getPayment(GetPaymentData $getPaymentData): ApiResponseData
    {
        try {
            $response = $this->apiClient->getPayment($getPaymentData);

            return new ApiResponseData(
                data: PaymentData::from($response)
            );
        } catch (ApiClientException $e) {
            throw $e;
        }
    }
}
