<?php

namespace Uca\Payments\Mappers;

use Illuminate\Http\Client\RequestException;
use Uca\Payments\Data\Response\ApiResposeErrorData;

class ApiErrorMapper
{
    public static function fromRequestException(RequestException $e): ApiResposeErrorData
    {
        $response = $e->response;
        $body = $response?->json() ?? [];

        return new ApiResposeErrorData(
            error_uuid: $body['error_uuid'] ?? uniqid('api_error_', true),
            message: $body['message'] ?? $e->getMessage(),
            exception: $body['exception'] ?? $e::class,
            validation_errors: $body['validation_errors'] ?? null,
            request: null,
            debug: [
                'status' => $response?->status(),
                'raw_body' => $response?->body(),
            ]
        );
    }
}
