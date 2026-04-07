<?php

namespace Uca\Payments\Data\Requests\Remote;

use Illuminate\Support\Carbon;
use Uca\Payments\Enums\PaymentStatusEnum;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * @param  string|null  $sort
 * @param  string|null  $criteria
 * @param  string|null  $external_reference
 * @param  string|null  $range
 * @param  string|null  $begin_date
 * @param  string|null  $end_date
 * @param  PaymentStatusEnum|null  $status
 * @param  string|null  $gateway_transaction_id
 * @param  string|null  $store_id
 * @param  string|null  $pos_id
 * @param  string|null  $collector_id
 * @param  string|null  $payer_id
 * @param  int|null  $offset
 * @param  int|null  $limit
 */
class SearchPaymentsData extends Data
{
    public function __construct(

        #[Rule('in:date_approved,date_created,date_last_updated,id,money_release_date')]
        public ?string $sort = null,

        #[Rule('in:asc,desc')]
        public ?string $criteria = null,

        public ?string $external_reference = null,

        #[Rule('in:date_created,date_last_updated,date_approved,money_release_date')]
        public ?string $range = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[Rule('date')]
        public ?Carbon $begin_date = null,

        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        #[Rule(['date', 'after_or_equal:begin_date'])]
        public ?Carbon $end_date = null,

        #[Rule([new Enum(PaymentStatusEnum::class)])]
        public ?PaymentStatusEnum $status = null,

        public ?string $gateway_transaction_id = null,
        public ?string $store_id = null,
        public ?string $pos_id = null,
        public ?string $collector_id = null,
        public ?string $payer_id = null,

        #[Rule('integer')]
        public ?int $offset = 0,

        #[Rule('integer')]
        public ?int $limit = null,
    ) {
        $this->limit ??= config('payment-gateways.search.limit');
        $this->begin_date?->startOfDay();
        $this->end_date?->endOfDay();
    }
}
