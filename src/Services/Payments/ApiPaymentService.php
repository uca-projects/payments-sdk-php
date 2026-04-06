<?php

namespace Uca\Payments\Services\Payments;

use Uca\Payments\Clients\ApiPaymentClient;
use Uca\Payments\Data\PaginationData;
use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Payments\SearchPaymentsData;
use Uca\Payments\Data\Response\ApiCollectionResponseData;
use Uca\Payments\Exceptions\ApiClientException;

class ApiPaymentService
{
    public function search(SearchPaymentsData $searchPaymentsData): ApiCollectionResponseData
    {
        try {
            $apiClient = new ApiPaymentClient();
            $response = $apiClient->search($searchPaymentsData);

            return new ApiCollectionResponseData(
                data: PaymentData::collect($response['data'])->toCollection(),
                pagination: PaginationData::from($response['pagination']),
                meta: $response['meta'] ?? null
            );
        } catch (ApiClientException $e) {
            //dd($e->error->debug);
            dd(json_decode($e->error->debug['raw_body'], true));
            throw $e;
        }
    }
}
