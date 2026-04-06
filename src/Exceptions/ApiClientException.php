<?php

namespace Uca\Payments\Exceptions;

use RuntimeException;
use Uca\Payments\Data\Response\ApiResposeErrorData;

class ApiClientException extends RuntimeException
{
    public function __construct(
        public ApiResposeErrorData $error
    ) {
        parent::__construct($error->message);
    }

    public static function fromErrorData(ApiResposeErrorData $error): self
    {
        return new self($error);
    }
}
