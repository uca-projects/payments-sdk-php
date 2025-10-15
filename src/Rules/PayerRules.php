<?php

namespace Uca\Payments\Rules;

use Illuminate\Validation\Rule;

class PayerRules
{
    public static function rules(): array
    {
        return [
            'payer' => ['required', 'array'],
            'payer.payer_reference' => ['required', 'string'],
            'payer.name' => ['required', 'string'],
            'payer.surname' => ['required', 'string'],
            'payer.email' => ['required', 'email'],
            'payer.doc_type' => ['required', 'string', Rule::in(['DNI', 'CUIT'])],
            'payer.doc_number' => ['required', 'string'],
            'payer.billing_address' => ['nullable', 'array', new RequiredArrayKeys(['street_name'])],
        ];
    }
}
