<?php

namespace Uca\Payments\Data\Response;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AbstractApiResponseData extends Data
{
    protected int $status_code = 200;

    protected string $status_text;

    public function setStatusCode(int $status_code): static
    {
        $this->status_code = $status_code;
        $this->status_text = HttpFoundationResponse::$statusTexts[$status_code];

        return $this;
    }

    /**
     * Override the default Spatie\LaravelData\Concerns\ResponsableData behavior.
     * By default, it returns 201 (HTTP_CREATED) for POST requests. We override it
     * here to always return the explicitly set status code for this response.
     */
    public function calculateResponseStatus(Request $request): int
    {
        return $this->status_code;
    }
}
