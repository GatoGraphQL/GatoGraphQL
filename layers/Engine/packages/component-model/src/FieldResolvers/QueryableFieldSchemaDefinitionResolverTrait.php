<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait QueryableFieldSchemaDefinitionResolverTrait
{
    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getFieldDataFilteringModule($typeResolver, $fieldName);
        }
        return null;
    }
}
