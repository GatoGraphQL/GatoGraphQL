<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait QueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getFieldDataFilteringModule($relationalTypeResolver, $fieldName);
        }
        return null;
    }
}
