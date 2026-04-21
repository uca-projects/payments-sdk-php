<?php

namespace Uca\Payments\Traits;

use Spatie\LaravelData\Data;

trait DataMergerTrait
{
    /**
     * Devuelve una nueva instancia del DTO combinando $this con $other.
     * Los atributos de $other pisan los de $this solo si no son null.
     * Los atributos de $this se mantienen cuando $other tiene null.
     *
     * @return static
     */
    public function mergePreferNonNull(Data $other): static
    {
        return static::from(
            self::mergeArrays($this->toArray(), $other->toArray())
        );
    }

    private static function mergeArrays(array $primary, array $fallback): array
    {
        foreach ($fallback as $key => $fallbackValue) {
            if ($fallbackValue === null) {
                continue;
            }

            if (is_array($fallbackValue) && isset($primary[$key]) && is_array($primary[$key])) {
                $primary[$key] = self::mergeArrays($primary[$key], $fallbackValue);
            } else {
                $primary[$key] = $fallbackValue;
            }
        }

        return $primary;
    }
}
