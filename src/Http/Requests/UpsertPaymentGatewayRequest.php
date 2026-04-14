<?php

namespace Uca\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Uca\Payments\Enums\GatewayEnum;

class UpsertPaymentGatewayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::enum(GatewayEnum::class)],
            'credential' => ['required', 'array'],
            'alias' => ['required', 'string'],
            'id' => ['required', 'uuid'],
        ];
    }
}
