<?php

namespace Uca\Payments\Data;

use Spatie\LaravelData\Data;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ItemData',
    title: 'ItemData',
    description: 'Item data',
    required: ["item_reference", "title", "quantity", "unit_price"],
    properties: [
        new OA\Property(property: 'item_reference', type: 'string', example: 'SKU-1234', description: 'Item reference (e.g. SKU or internal code)'),
        new OA\Property(property: 'title', type: 'string', example: 'Product title', description: 'Title or name of the product'),
        new OA\Property(property: 'description', type: 'string', example: 'Product description', description: 'Description of the product'),
        new OA\Property(property: 'quantity', type: 'integer', example: 1, description: 'Quantity (default 1 if not specified)'),
        new OA\Property(property: 'unit_price', type: 'number', format: 'float', example: 10.5, description: 'Unit price with up to 2 decimal places'),
    ]
)]
class ItemData extends Data
{
    /**
     * @param string|null $item_reference
     * @param string $title
     * @param string|null $description
     * @param int $quantity
     * @param float $unit_price
     */
    public function __construct(
        public ?string $item_reference,
        public string $title,
        public ?string $description,
        public int $quantity,
        public float $unit_price
    ) {}
}
