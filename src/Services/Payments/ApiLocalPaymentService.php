<?php

namespace Uca\Payments\Services\Payments;

use Spatie\LaravelData\DataCollection;
use Uca\Payments\Clients\ApiLocalPaymentsClient;
use Uca\Payments\Data\PaginationData;
use Uca\Payments\Data\Payment\PaymentCollectionData;
use Uca\Payments\Data\Payment\PaymentData;
use Uca\Payments\Data\Requests\Local\GetPaymentData;
use Uca\Payments\Data\Requests\Local\SearchPaymentsData;
use Uca\Payments\Enums\PaymentStatusEnum;
use Uca\Payments\Exceptions\ApiClientException;

class ApiLocalPaymentService
{
    public function __construct(
        private ApiLocalPaymentsClient $apiLocalPaymentsClient,
        private SearchPaymentsData $searchCriteria
    ) {
        $this->searchCriteria = SearchPaymentsData::from([]);
    }

    public function byExternalReference(string $value): self
    {
        $this->searchCriteria->external_reference_like = $value;
        return $this;
    }

    public function byPreferenceId(string $value): self
    {
        $this->searchCriteria->preference_id = $value;
        return $this;
    }

    public function byClientId(string $value): self
    {
        $this->searchCriteria->client_id = $value;
        return $this;
    }

    public function byPaymentGatewayId(string $value): self
    {
        $this->searchCriteria->payment_gateway_id = $value;
        return $this;
    }

    public function byGatewayTransactionId(string $value): self
    {
        $this->searchCriteria->gateway_transaction_id = $value;
        return $this;
    }

    public function byStatus(string|PaymentStatusEnum $value): self
    {
        if (is_string($value)) {
            $value = PaymentStatusEnum::tryFrom(strtoupper($value));
        }
        $this->searchCriteria->status = $value;
        return $this;
    }

    public function minAmount(float $value): self
    {
        $this->searchCriteria->min_amount = $value;
        return $this;
    }

    public function maxAmount(float $value): self
    {
        $this->searchCriteria->max_amount = $value;
        return $this;
    }

    public function minCreatedAt(string $value): self
    {
        $this->searchCriteria->min_created_at = $value;
        return $this;
    }

    public function maxCreatedAt(string $value): self
    {
        $this->searchCriteria->max_created_at = $value;
        return $this;
    }

    public function offset(int $value): self
    {
        $this->searchCriteria->offset = $value;
        return $this;
    }

    public function limit(int $value): self
    {
        $this->searchCriteria->limit = $value;
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
