<?php

namespace Uca\Payments\Data\Payment;


use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Uca\Payments\Data\PaginationData;

class PaymentCollectionData extends Data
{
    public function __construct(
        public PaginationData $pagination,
        #[DataCollectionOf(PaymentData::class)]
        public Collection $items
    ) {}
}
