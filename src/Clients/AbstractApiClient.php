<?php

namespace Uca\Payments\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Uca\Payments\Exceptions\ApiClientException;
use Uca\Payments\Mappers\ApiErrorMapper;
use Illuminate\Support\Facades\Config;
use Uca\Payments\Clients\ApiClientConfig;
use Exception;

abstract class AbstractApiClient
{
    private const ENDPOINTS = [
        'auth_token' => '/api/auth/token',
    ];

    public function __construct()
    {
        $uca_payments_sdk_config = Config::get('uca-payments-sdk');
        ApiClientConfig::setConfig(
            $uca_payments_sdk_config['base_url'],
            $uca_payments_sdk_config['client_key'],
            $uca_payments_sdk_config['client_secret'],
            $uca_payments_sdk_config['token_ttl']
        );
    }

    protected function withRetry(): PendingRequest
    {
        return Http::retry(
            times: 2,
            sleepMilliseconds: 10,
            when: function (Exception $exception) {
                if (
                    $exception instanceof RequestException &&
                    $exception->response->status() === 401
                ) {
                    $this->invalidateAccessToken();
                    return true;
                }

                return false;
            },
            throw: true
        )
            ->withToken($this->getAccessToken())
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])->acceptJson();
    }

    private function invalidateAccessToken(): void
    {
        Cache::forget($this->getCacheKey() . ':access_token');
    }

    private function fetchAndStoreAccessToken(): string
    {
        return Cache::lock($this->getCacheKey() . ':access_token_lock', 5)
            ->block(2, function () {
                // doble check para evitar refresh innecesario
                if (Cache::has($this->getCacheKey() . ':access_token')) {
                    return Cache::get($this->getCacheKey() . ':access_token');
                }

                $token = $this->fetchNewAccessToken();

                return $token;
            });
    }

    private function getCacheKey(): string
    {
        return 'uca-payments-sdk:' . ApiClientConfig::getClientKey();
    }

    private function fetchNewAccessToken(): string
    {
        $payload = [
            'client_key' => ApiClientConfig::getClientKey(),
            'client_secret' => ApiClientConfig::getClientSecret()
        ];

        $response = Http::acceptJson()
            ->throw()
            ->post(ApiClientConfig::getBaseUrl() . self::ENDPOINTS['auth_token'], $payload);

        $token = $response->json('access_token');

        return $token;
    }

    final protected function getAccessToken(): string
    {
        return Cache::remember(
            $this->getCacheKey() . ':access_token',
            now()->addMinutes(),
            fn() => $this->fetchAndStoreAccessToken()
        );
    }

    final protected function doGet(string $endpoint, array $params = []): array
    {
        try {
            $response = $this->withRetry()
                ->withUrlParameters($params)
                ->get(ApiClientConfig::getBaseUrl() . $endpoint);
            return $response->json();
        } catch (RequestException $e) {
            throw ApiClientException::fromErrorData(
                ApiErrorMapper::fromRequestException($e)
            );
        }
    }

    final protected function doPost(string $endpoint, array $params): array
    {
        try {
            $response = $this->withRetry()
                ->post(ApiClientConfig::getBaseUrl() . $endpoint, $params);
            return $response->json();
        } catch (RequestException $e) {
            throw ApiClientException::fromErrorData(
                ApiErrorMapper::fromRequestException($e)
            );
        }
    }

    final protected function doPut(string $endpoint, array $url_params = [], array $body_params = []): array
    {
        try {
            $response = $this->withRetry()
                ->withUrlParameters($url_params)
                ->put(ApiClientConfig::getBaseUrl() . $endpoint, $body_params);
            return $response->json();
        } catch (RequestException $e) {
            throw ApiClientException::fromErrorData(
                ApiErrorMapper::fromRequestException($e)
            );
        }
    }
}
