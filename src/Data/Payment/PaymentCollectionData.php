<?php

namespace Uca\Payments\Data\Payment;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Uca\Payments\Data\PaginationData;

class PaymentCollectionData extends Data
{
    public function __construct(
        public PaginationData $pagination,
        #[DataCollectionOf(PaymentData::class)]
        public DataCollection $items
    ) {}
}
