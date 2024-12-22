<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class StringListValueJSONObjectScalarTypeResolver extends AbstractScalarListValueJSONObjectScalarTypeResolver
{
    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function getTypeName(): string
    {
        return 'StringListValueJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object where values are lists of strings (`null` values not accepted)', 'extended-schema-commons');
    }

    protected function canCastJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): bool {
        return true;
    }

    protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool {
        return (string) $value;
    }
}
