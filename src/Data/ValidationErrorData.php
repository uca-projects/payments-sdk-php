<?php

namespace Uca\PaymentsSharedClass\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ValidationErrorData',
    title: 'ValidationErrorData',
    description: 'Data structure for validation errors',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'ERROR', description: 'Status of the response'),
        new OA\Property(property: 'status_code', type: 'integer', example: 422, description: 'HTTP status code'),
        new OA\Property(property: 'message', type: 'string', example: 'Error validating form Payment', description: 'Error message'),
        new OA\Property(property: 'validation_errors', type: 'array', description: 'List of validation errors', example: ['field_name: The field is required.']),
        new OA\Property(property: 'request', type: 'object', nullable: true, description: 'Original request data'),
        new OA\Property(property: 'additional_info', type: 'object', nullable: true, description: 'Additional information about the error'),
        new OA\Property(property: 'exception', type: 'string', example: 'App\\Exceptions\\ValidationRequestException', nullable: true, description: 'Exception class name'),
    ]
)]
class ValidationErrorData extends Data
{
    public function __construct(
        public string $status,
        public int $status_code,
        public string $message,
        public array $validation_errors,
        public ?array $request,
        public ?array $additional_info,
        public ?string $exception,
    ) {}
}
