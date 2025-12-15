<?php

return [
    'payment-gateway-url' => env('PAYMENT_GATEWAY_URL'),
    'client_key' => env('PAYMENT_GATEWAY_CLIENT_KEY'),
    'client_secret' => env('PAYMENT_GATEWAY_CLIENT_SECRET'),
    'token_ttl' => env('PAYMENT_GATEWAY_TOKEN_TTL', 10080), // 1 week
];
