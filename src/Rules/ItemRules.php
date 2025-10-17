<?php

namespace Uca\Payments\Rules;

class ItemRules
{
    public static function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.amount' => ['prohibited'],
            'items.*.title' => ['required', 'string'],
            'items.*.unit_price' => ['required', 'numeric', 'min:5'],
            'items.*.quantity' => ['required', 'integer:strict', 'min:1'],
            'items.*.item_reference' => ['required'],
            'items.*.description' => ['nullable'],
            'items.*.amount' => ['prohibited'],
        ];
    }
}
