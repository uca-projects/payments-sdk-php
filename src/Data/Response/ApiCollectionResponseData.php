<?php

namespace Uca\Payments\Data\Response;

use Uca\Payments\Data\PaginationData;

class ApiCollectionResponseData extends AbstractApiResponseData
{
    public function __construct(
        public iterable $data,
        public PaginationData $pagination,
        public ?array $meta = null,
    ) {}
}
