<?php

namespace Uca\Payments\Casts;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class CarbonDateCast implements Cast
{
    public function __construct(private string $format = 'Y-m-d') {}

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            $value = substr($value, 0, 10); // YYYY-MM-DD
        }

        return Carbon::createFromFormat($this->format, $value);
    }
}
