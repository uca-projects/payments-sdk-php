@props(['apiCollectionResponse' => null, 'itemLabel' => 'resultados'])

@php
$pagination = $apiCollectionResponse?->pagination ?? null;
$itemsCount = isset($apiCollectionResponse?->data) && is_countable($apiCollectionResponse->data) ? count($apiCollectionResponse->data) : 0;
@endphp

@if(isset($pagination) && $pagination instanceof \Uca\Payments\Data\PaginationData)
<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        @if($pagination->total !== null)
        Mostrando {{ $itemsCount }} de {{ $pagination->total }} {{ $itemLabel }}
        @else
        Mostrando {{ $itemsCount }} {{ $itemLabel }}
        @endif
    </div>
    <div>
        @if($pagination->offset > 0)
        <a href="{{ request()->fullUrlWithQuery(['offset' => max(0, $pagination->offset - $pagination->limit)]) }}" class="btn btn-outline-secondary btn-sm">&laquo; Anterior</a>
        @endif

        @if($pagination->hasMore)
        <a href="{{ request()->fullUrlWithQuery(['offset' => $pagination->offset + $pagination->limit]) }}" class="btn btn-outline-secondary btn-sm">Siguiente &raquo;</a>
        @endif
    </div>
</div>
@endif