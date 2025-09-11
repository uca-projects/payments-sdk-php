<?php

namespace Uca\PaymentsSharedClass\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredArrayKeys implements ValidationRule
{
    /**
     * The required keys.
     *
     * @var array
     */
    protected array $requiredKeys;

    /**
     * Create a new rule instance.
     */
    public function __construct(array $requiredKeys)
    {
        $this->requiredKeys = $requiredKeys;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            $fail(__('validation.array', ['attribute' => $attribute]));
            return;
        }

        foreach ($this->requiredKeys as $key) {
            if (! array_key_exists($key, $value)) {
                $fail(__('validation.required_key', ['attribute' => $attribute, 'key' => $key]));
            }
        }
    }
}
