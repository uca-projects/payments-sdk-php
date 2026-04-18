<?php

namespace Uca\Payments\Traits;

use Spatie\LaravelData\Data;

final class DataMergerTrait
{
    /**
     * @template T of Data
     * @param T $primary
     * @param Data $fallback
     * @return T
     */
    public static function mergePreferNonNull(
        Data $primary,
        Data $fallback
    ): Data {
        return $primary::from(
            self::mergeArrays(
                $primary->toArray(),
                $fallback->toArray()
            )
        );
    }

    private static function mergeArrays(array $primary, array $fallback): array
    {
        foreach ($fallback as $key => $fallbackValue) {
            if (!array_key_exists($key, $primary) || $primary[$key] === null) {
                $primary[$key] = $fallbackValue;
                continue;
            }

            if (is_array($primary[$key]) && is_array($fallbackValue)) {
                $primary[$key] = self::mergeArrays(
                    $primary[$key],
                    $fallbackValue
                );
            }
        }

        return $primary;
    }
}
