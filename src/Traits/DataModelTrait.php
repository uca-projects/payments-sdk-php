<?php

namespace Uca\Payments\Traits;

trait DataModelTrait
{
    /**
     * Transforma el DTO actual en un modelo Eloquent de forma recursiva.
     * 
     * @template T
     * @param class-string<T> $modelClass FQCN del modelo a instanciar (ej: Payment::class).
     * @param array<class-string, class-string> $dtoModelMap Mapa de [DTOClass => ModelClass] 
     *        usado para resolver y transformar automáticamente las relaciones detectadas.
     * @return T Instancia del modelo con atributos y relaciones (setRelation) cargadas.
     */
    public function toModel(string $modelClass, array $dtoModelMap = []): mixed
    {
        // 1. Obtener propiedades que son relaciones Data (via reflection)
        $relationKeys = $this->resolveDataRelationKeys();

        // 2. Atributos escalares (sin nulls, sin relaciones)
        $attributes = array_filter(
            $this->toArray(),
            fn($v, $k) => !is_null($v) && !in_array($k, $relationKeys),
            ARRAY_FILTER_USE_BOTH
        );

        $model = new $modelClass($attributes);

        // 3. Relaciones: auto-detectadas via reflection + resueltas con el mapa
        foreach ($relationKeys as $property) {
            $value = $this->{$property};
            if (is_null($value)) continue;

            // Si es colección (array de Data)
            if (is_array($value)) {
                /** 
                 * Las propiedades tipadas como ?array con #[DataCollectionOf(ItemData::class)] son detectadas como array por reflection, 
                 * pero el tipo del elemento no puede inferirse del atributo PHP fácilmente. 
                 * Para colecciones, se resuelve inspeccionando el primer elemento en runtime (get_class($value[0])).
                 */
                $firstItem = $value[0] ?? null;
                if (is_object($firstItem)) {
                    $itemDtoClass = get_class($firstItem);
                    if (isset($dtoModelMap[$itemDtoClass])) {
                        $collection = collect($value)
                            ->map(fn($item) => is_object($item) ? $item->toModel($dtoModelMap[$itemDtoClass], $dtoModelMap) : $item);
                        $model->setRelation($property, $collection);
                    }
                }
                continue;
            }

            // Si es relación singular
            if (is_object($value)) {
                $dtoClass = get_class($value);
                if (isset($dtoModelMap[$dtoClass])) {
                    $model->setRelation(
                        $property,
                        $value->toModel($dtoModelMap[$dtoClass], $dtoModelMap)
                    );
                }
            }
        }

        return $model;
    }

    private function resolveDataRelationKeys(): array
    {
        $keys = [];
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $type = $prop->getType();
            if (!$type instanceof \ReflectionNamedType) continue;
            $typeName = $type->getName();
            // Es un Data o un array (posible colección de Data)
            if (is_subclass_of($typeName, \Spatie\LaravelData\Data::class) || $typeName === 'array') {
                $keys[] = $prop->getName();
            }
        }
        return $keys;
    }
}
