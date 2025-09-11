<?php

namespace Uca\PaymentsSharedClass\Rules;

class ItemRules
{
    public static function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.amount' => ['prohibited'],
            'items.*.title' => ['required', 'string'],
            'items.*.unit_price' => ['required', 'numeric', 'min:5'],
            'items.*.quantity' => ['required', 'integer:strict', 'min:1'],
            'items.*.item_reference' => ['required'],
            'items.*.description' => ['nullable'],
        ];
    }
}
