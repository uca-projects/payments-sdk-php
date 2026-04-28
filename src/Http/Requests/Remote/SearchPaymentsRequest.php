<?php

namespace Uca\Payments\Http\Requests\Remote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Uca\Payments\Enums\PaymentStatusEnum;

class SearchPaymentsRequest extends FormRequest
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
            'payment_gateway_id' => ['required', 'uuid'],
            'sort' => ['nullable', 'string', 'in:date_approved,date_created,date_last_updated,id,money_release_date'],
            'criteria' => ['nullable', 'string', 'in:asc,desc'],
            'external_reference' => ['nullable', 'string'],
            'range' => ['nullable', 'string', 'in:date_created,date_last_updated,date_approved,money_release_date'],
            'begin_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:begin_date'],
            'status' => ['nullable', 'string', Rule::enum(PaymentStatusEnum::class)],
            'gateway_transaction_id' => ['nullable', 'string'],
            'store_id' => ['nullable', 'string'],
            'pos_id' => ['nullable', 'string'],
            'collector_id' => ['nullable', 'string'],
            'payer_id' => ['nullable', 'string'],
            'offset' => ['nullable', 'integer'],
            'limit' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'payment_gateway_id' => $this->route('payment_gateway_id'),
        ]);
    }
}
