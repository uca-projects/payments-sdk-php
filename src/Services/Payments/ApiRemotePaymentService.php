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
        private ApiRemotePaymentsClient $apiRemotePaymentsClient,
        private SearchPaymentsData $searchCriteria
    ) {
        $this->searchCriteria = SearchPaymentsData::from([]);
    }

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

    public function byExternalReference(string $value): self
    {
        $this->searchCriteria->external_reference = $value;
        return $this;
    }

    public function byGatewayTransactionId(string $value): self
    {
        $this->searchCriteria->gateway_transaction_id = $value;
        return $this;
    }

    public function byPaymentGatewayId(string $value): self
    {
        $this->searchCriteria->payment_gateway_id = $value;
        return $this;
    }

    public function get(): PaymentCollectionData
    {
        return $this->search($this->searchCriteria);
    }

    public function getSearchData(): SearchPaymentsData
    {
        return $this->searchCriteria;
    }
}
