<?php

namespace Uca\Payments\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Uca\Payments\Enums\PaymentStatusEnum;

class PaymentSearchRequest extends FormRequest
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
            'external_reference_like' => ['nullable', 'string', 'max:255'],
            'preference_id' => ['nullable', 'uuid', 'exists:preferences,id'],
            'client_id' => ['nullable', 'uuid', 'exists:clients,id'],
            'payment_gateway_id' => ['nullable', 'uuid', 'exists:payment_gateways,id'],
            'gateway_transaction_id' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', Rule::enum(PaymentStatusEnum::class)],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0', 'gte:min_amount'],
            'min_created_at' => ['nullable', 'date'],
            'max_created_at' => ['nullable', 'date', 'after_or_equal:min_created_at'],
            'offset' => ['nullable', 'integer', 'min:0'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:' . config('uca-payments-sdk.max_search_results')],
        ];
    }
}
