<?php

namespace Uca\Payments\Traits;

use Uca\Payments\Filters\QueryFilter;

trait Filterable
{
    public function scopeFilter($query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
