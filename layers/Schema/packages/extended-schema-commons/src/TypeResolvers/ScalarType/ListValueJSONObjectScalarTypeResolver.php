<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

class ListValueJSONObjectScalarTypeResolver extends AbstractListValueJSONObjectScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'ListValueJSONObject';
    }

    protected function canValueBeNullable(): bool
    {
        return false;
    }
}
