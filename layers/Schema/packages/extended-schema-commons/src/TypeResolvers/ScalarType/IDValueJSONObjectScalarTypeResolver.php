<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class IDValueJSONObjectScalarTypeResolver extends AbstractScalarValueJSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IDValueJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object where values are IDs (strings or integers)', 'extended-schema-commons');
    }

    protected function canCastJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): bool {
        return is_string($value) || is_int($value);
    }

    protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool {
        return $value;
    }
}
