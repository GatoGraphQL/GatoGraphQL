<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class StringValueJSONObjectScalarTypeResolver extends AbstractScalarValueJSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'StringValueJSONObject';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Custom scalar representing a JSON Object where values are strings', 'extended-schema-commons');
    }

    protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool {
        return (string) $value;
    }
}
