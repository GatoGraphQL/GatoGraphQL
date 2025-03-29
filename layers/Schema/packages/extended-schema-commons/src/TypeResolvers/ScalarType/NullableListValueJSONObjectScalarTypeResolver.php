<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class NullableListValueJSONObjectScalarTypeResolver extends AbstractListValueJSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'NullableListValueJSONObject';
    }

    protected function canValueBeNullable(): bool
    {
        return true;
    }
}
