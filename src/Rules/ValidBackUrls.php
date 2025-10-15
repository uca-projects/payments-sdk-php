<?php

namespace Uca\Payments\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ValidBackUrls implements ValidationRule
{
    protected array $requiredKeys;
    private array $allowedKeys = ['success', 'failure', 'pending', 'unique'];

    public function __construct(array $requiredKeys = [])
    {
        $this->requiredKeys = $requiredKeys;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $array_diff = array_diff(array_keys($value), $this->allowedKeys);
        if (!empty($array_diff)) {
            $fail("The :attribute has a not allowed keys: " . implode(', ', $array_diff) . ". Allowed keys are: " . implode(', ', $this->allowedKeys));
        }

        foreach ($this->requiredKeys as $key) {
            // Si hay algún índice en back_urls que no esté en requiredKeys, entra al if
            if (!array_key_exists($key, $value)) {
                $fail("The :attribute must contain the key '{$key}'.");
            } else {
                // Valido las urls de los índices requeridos
                $validator = Validator::make(
                    [$key => $value[$key]],
                    [$key => 'url']
                );

                if ($validator->fails()) {
                    $fail("The :attribute.{$key} must be a valid URL.");
                }
            }
        }
    }
}
