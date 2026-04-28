<?php

namespace Uca\Payments\Data\Response;

class ApiResponseData extends AbstractApiResponseData
{
    public function __construct(
        public mixed $data = []
    ) {
        $this->data ??= [];
    }
}
