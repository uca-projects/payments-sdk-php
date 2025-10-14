<?php

namespace Uca\PaymentsSharedClass\ervices;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Uca\PaymentsSharedClass\Data\PaymentGatewayData;

class ApiPaymentService
{
    public function byExternalReference(string $payment_gateway_id, string $external_reference): array
    {
        $params = [
            'externalReference' => $external_reference,
            'paymentGatewayId' => $payment_gateway_id
        ];
        $endpoint = 'payment/remote/{paymentGatewayId}/external_reference/{externalReference}';
        return $this->doGet($endpoint, $params);
    }

    public function byTransactionId(string $payment_gateway_id, string $transaction_id): array
    {
        $params = [
            'transactionId' => $transaction_id,
            'paymentGatewayId' => $payment_gateway_id
        ];
        $endpoint = 'payment/remote/{paymentGatewayId}/gateway_transaction_id/{transactionId}';
        return $this->doGet($endpoint, $params);
    }

    public function byPreferenceId(string $preference_id): array
    {
        $params = ['preference_id' => $preference_id];
        return $this->search($params);
    }

    public function byId(string $id): array
    {
        $params = ['id' => $id];
        return $this->search($params);
    }

    public function search(array $params = []): array
    {
        $endpoint = 'payment/local/search?';
        // Tomamos solo las keys
        $keys = array_keys($params);
        // Genera "/{id}/{status}/{date}"
        $endpoint .= implode('&', array_map(fn($k) => $k . '={' . $k . '}', $keys));
        return $this->doGet($endpoint, $params);
    }

    public function updateOrCreatePaymentGateway(PaymentGatewayData $payment_gateway): array
    {
        $response = $this->doPost('payment-gateway', $payment_gateway->toArray());
        return  $response['data'];
    }

    private function doGet(string $endpoint, array $params): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->acceptJson()
            ->withUrlParameters($params)
            ->get(config('payments-shared-class.payment-gateway-url') . '/api/' . $endpoint);

        $data = $response->json();

        if ($response->status() !== HttpFoundationResponse::HTTP_OK) {
            // TO DO reemplazar por un log de error para que no vea el cliente
            if ($response->status() === HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY) {
                foreach ($data['validation_errors'] as $field => $messages) {
                    $data['message'] .= ': ' . $messages[0];
                }
            }

            throw new HttpException($response->status(), $data['message'] ?? 'Error desconocido');
        } else {
            return $data;
        }
    }

    private function doPost(string $endpoint, array $params): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->acceptJson()
            ->post(config('payments-shared-class.payment-gateway-url') . '/api/' . $endpoint, $params);

        if ($response->status() !== HttpFoundationResponse::HTTP_OK && $response->status() !== HttpFoundationResponse::HTTP_CREATED) {
            // TO DO reemplazar por un log de error para que no vea el cliente
            throw new HttpException($response->status(), $response->json()['message']);
        }
        return  $response->json();
    }
}
