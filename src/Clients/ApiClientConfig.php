<?php

namespace Uca\Payments\Clients;

class ApiClientConfig
{
    protected static $base_url;
    protected static $client_key;
    protected static $client_secret;
    protected static $token_ttl;

    public static function setConfig(?string $base_url, ?string $client_key, ?string $client_secret, ?int $token_ttl): void
    {
        self::$base_url = $base_url;
        self::$client_key = $client_key;
        self::$client_secret = $client_secret;
        self::$token_ttl = $token_ttl;
    }

    public static function getBaseUrl(): string
    {
        if (empty(self::$base_url)) {
            throw new \Exception('PAYMENT_GATEWAY_BASE_URL is not set');
        }
        return self::$base_url;
    }

    public static function setBaseUrl($base_url): void
    {
        self::$base_url = $base_url;
    }

    public static function getTokenTtl(): int
    {
        return self::$token_ttl;
    }

    public static function setTokenTtl($token_ttl): void
    {
        self::$token_ttl = $token_ttl;
    }

    public static function getClientKey(): string
    {
        if (empty(self::$client_key)) {
            throw new \Exception('PAYMENT_GATEWAY_CLIENT_KEY is not set');
        }
        return self::$client_key;
    }

    public static function setClientKey($client_key): void
    {
        self::$client_key = $client_key;
    }

    public static function getClientSecret(): string
    {
        if (empty(self::$client_secret)) {
            throw new \Exception('PAYMENT_GATEWAY_CLIENT_SECRET is not set');
        }
        return self::$client_secret;
    }

    public static function setClientSecret($client_secret): void
    {
        self::$client_secret = $client_secret;
    }
}
