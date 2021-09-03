<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait QueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getFieldDataFilteringModule($typeResolver, $fieldName);
        }
        return null;
    }
}
