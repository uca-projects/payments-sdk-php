<?php

namespace Uca\Payments\Traits;

use App\Models\Payment;
use Illuminate\Support\Arr;

trait DataModelTrait
{
    public function toModel(): array
    {
        return Arr::only($this->toArray(), (new Payment())->getFillable());
    }
}
