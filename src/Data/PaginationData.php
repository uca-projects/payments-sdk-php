<?php

namespace Uca\Payments\Data;

use Spatie\LaravelData\Data;

class PaginationData extends Data
{
    public function __construct(
        public int $limit,
        public int $offset,
        public ?int $total,
        public bool $hasMore,
    ) {}
}
