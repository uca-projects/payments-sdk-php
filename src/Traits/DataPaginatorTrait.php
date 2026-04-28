<?php

namespace Uca\Payments\Traits;

use Illuminate\Database\Eloquent\Builder;
use Uca\Payments\Data\PaginationData;

trait DataPaginatorTrait
{
    /**
     * Aplica limit y offset a la consulta.
     *
     * @param Builder $query
     * @param int $offset
     * @param ?int $limit
     * @return PaginationData
     */
    public function paginationFromModel(Builder $query, ?int $offset = 0, ?int $limit = null): PaginationData
    {
        $total = (clone $query)->count();

        $limit ??= (int) config('uca-payments-sdk.max_search_results', 100);

        $query->limit($limit);
        $query->offset($offset);

        return new PaginationData(
            limit: $limit,
            offset: $offset,
            total: $total,
            hasMore: ($offset + $query->get()->count()) < $total
        );
    }
}
