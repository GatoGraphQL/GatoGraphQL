<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class IntValueJSONObjectScalarTypeResolver extends AbstractScalarValueJSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IntValueJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object where values are integers', 'extended-schema-commons');
    }

    protected function canCastJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): bool {
        return is_numeric($value);
    }

    protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool {
        return (int) $value;
    }
}
