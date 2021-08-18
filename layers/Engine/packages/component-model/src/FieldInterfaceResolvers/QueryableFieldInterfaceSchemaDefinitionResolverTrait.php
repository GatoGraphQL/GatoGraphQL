<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

trait QueryableFieldInterfaceSchemaDefinitionResolverTrait
{
    use FieldInterfaceSchemaDefinitionResolverTrait;

    public function getFieldDataFilteringModule(string $fieldName): ?array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            /** @var QueryableFieldInterfaceSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            return $schemaDefinitionResolver->getFieldDataFilteringModule($fieldName);
        }
        return null;
    }
}
