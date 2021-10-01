<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;

abstract class AbstractQueryableSchemaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver implements QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    public function getFieldFilterInputContainerModule(string $fieldName): ?array
    {
        /** @var QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerModule($fieldName);
        }
        return null;
    }
    
    public function getSchemaFieldArgNameResolvers(string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgNameResolvers($filterDataloadingModule);
        }
        return parent::getSchemaFieldArgNameResolvers($fieldName);
    }
    
    public function getSchemaFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgDescription($filterDataloadingModule, $fieldArgName);
        }
        return parent::getSchemaFieldArgDescription($fieldName, $fieldArgName);
    }
    
    public function getSchemaFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgDefaultValue($filterDataloadingModule, $fieldArgName);
        }
        return parent::getSchemaFieldArgDefaultValue($fieldName, $fieldArgName);
    }
    
    public function getSchemaFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgTypeModifiers($filterDataloadingModule, $fieldArgName);
        }
        return parent::getSchemaFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
}
