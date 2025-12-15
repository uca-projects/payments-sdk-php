<?php

namespace Uca\Payments\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Uca\Payments\Data\PaymentGatewayData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;

class ApiPaymentService
{
    private const ENDPOINTS = [
        'external_reference' => 'payment/remote/{paymentGatewayId}/external_reference/{externalReference}',
        'transaction_id' => 'payment/remote/{paymentGatewayId}/gateway_transaction_id/{transactionId}',
        'search' => 'payment/local/search?',
        'payment_gateway' => 'payment-gateway',
        'auth_token' => 'auth/token',
    ];

    private string $baseUrl;
    private string $key;
    private string $secret;
    private string $token;
    public function __construct()
    {
        $this->baseUrl = config('uca-payments-sdk.payment-gateway-url') ?? '';
        $this->key = config('uca-payments-sdk.client_key') ?? '';
        $this->secret = config('uca-payments-sdk.client_secret') ?? '';

        if (empty($this->baseUrl) || empty($this->key) || empty($this->secret)) {
            throw new \InvalidArgumentException('Uca Payments SDK configuration is incomplete. Please check "payment-gateway-url", "client_key", and "client_secret".');
        }

        $this->token = Cache::get('payment_gateway_access_token') ?? $this->refreshAccessToken();
    }
    public function byExternalReference(string $payment_gateway_id, string $external_reference): array
    {
        $params = [
            'externalReference' => $external_reference,
            'paymentGatewayId' => $payment_gateway_id
        ];
        return $this->doGet(self::ENDPOINTS['external_reference'], $params);
    }

    public function byTransactionId(string $payment_gateway_id, string $transaction_id): array
    {
        $params = [
            'transactionId' => $transaction_id,
            'paymentGatewayId' => $payment_gateway_id
        ];
        return $this->doGet(self::ENDPOINTS['transaction_id'], $params);
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
        $endpoint = self::ENDPOINTS['search'];
        // Tomamos solo las keys
        $keys = array_keys($params);
        // Genera "/{id}/{status}/{date}"
        $endpoint .= implode('&', array_map(fn($k) => $k . '={' . $k . '}', $keys));
        return $this->doGet($endpoint, $params);
    }

    public function updateOrCreatePaymentGateway(PaymentGatewayData $payment_gateway): array
    {
        $response = $this->doPost(self::ENDPOINTS['payment_gateway'], $payment_gateway->toArray());
        return  $response['data'];
    }

    private function refreshAccessToken(): string
    {
        $response = Http::post($this->baseUrl . '/api/' . self::ENDPOINTS['auth_token'], [
            'client_key' => $this->key,
            'client_secret' => $this->secret
        ]);

        if ($response->successful()) {
            $this->token = $response->json('access_token');
            Cache::put('payment_gateway_access_token', $this->token, now()->addMinutes(config('uca-payments-sdk.token_ttl')));
            return $this->token;
        }

        throw new \Exception('Authentication failed: ' . $response->body());
    }

    private function doGet(string $endpoint, array $params, bool $retry = true): array
    {
        $response = Http::withToken($this->token)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->acceptJson()
            ->withUrlParameters($params)
            ->get(config('uca-payments-sdk.payment-gateway-url') . '/api/' . $endpoint);

        $data = $response->json();

        // Retry on 401 Unauthorized
        if ($response->status() === HttpFoundationResponse::HTTP_UNAUTHORIZED && $retry) {
            $this->token = $this->refreshAccessToken(); // Clear expired token
            return $this->doGet($endpoint, $params, false);
        }

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

    private function doPost(string $endpoint, array $params, bool $retry = true): array
    {
        $response = Http::withToken($this->token)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->acceptJson()
            ->post(config('uca-payments-sdk.payment-gateway-url') . '/api/' . $endpoint, $params);

        // Retry on 401 Unauthorized
        if ($response->status() === HttpFoundationResponse::HTTP_UNAUTHORIZED && $retry) {
            $this->token = $this->refreshAccessToken(); // Clear expired token
            return $this->doPost($endpoint, $params, false);
        }

        if ($response->status() !== HttpFoundationResponse::HTTP_OK && $response->status() !== HttpFoundationResponse::HTTP_CREATED) {
            // TO DO reemplazar por un log de error para que no vea el cliente
            throw new HttpException($response->status(), $response->json()['message']);
        }
        return  $response->json();
    }
}
