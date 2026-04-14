<?php

namespace Uca\Payments\Data\Response;

use Spatie\LaravelData\Optional;

class ApiResposeErrorData extends AbstractApiResponseData
{
    public function __construct(
        public string $error_uuid,
        public string $message,
        public string|Optional|null $exception = null,
        public array|Optional|null $validation_errors = null,
        public array|Optional|null $request = null,
        public array|Optional|null $response = null,
        public array|Optional|null $debug = null,
    ) {
        $this->message = app()->isProduction()
            ? __('An unexpected error occurred. Error Code: :errorCode', ['errorCode' => $this->error_uuid])
            : $this->message;

        if (!config('app.debug')) {
            $this->exception = Optional::create();
            $this->debug = Optional::create();
        }

        $this->request ??= Optional::create();
        $this->response ??= Optional::create();
        $this->validation_errors ??= Optional::create();
    }
}
