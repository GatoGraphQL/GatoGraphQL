<?php

declare(strict_types=1);

namespace PoP\API\TypeResolvers\EnumType;

use PoP\ComponentModel\Schema\SchemaDefinitionShapes;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class SchemaFieldShapeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'SchemaOutputShape';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            SchemaDefinitionShapes::FLAT,
            SchemaDefinitionShapes::NESTED,
        ];
    }
}
