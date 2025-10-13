<?php

namespace Uca\PaymentsSharedClass\Traits;

use Uca\PaymentsSharedClass\Filters\QueryFilter;

trait Filterable
{
    public function scopeFilter($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
