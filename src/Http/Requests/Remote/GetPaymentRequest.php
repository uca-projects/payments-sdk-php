<?php

namespace Uca\Payments\Http\Requests\Remote;

use Illuminate\Foundation\Http\FormRequest;

class GetPaymentRequest extends FormRequest
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
            'payment_gateway_id' => ['required', 'uuid', 'exists:payment_gateways,id'],
            'unique_field' => ['required', 'in:gateway_transaction_id,external_reference'],
            'value' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_gateway_id' => $this->route('payment_gateway_id'),
            'unique_field' => $this->route('unique_field'),
            'value' => $this->route('value'),
        ]);
    }
}
