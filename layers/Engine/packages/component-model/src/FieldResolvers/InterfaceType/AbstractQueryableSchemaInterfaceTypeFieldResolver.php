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

    public function getFieldArgNameResolvers(string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgNameResolvers($filterDataloadingModule);
        }
        return parent::getFieldArgNameResolvers($fieldName);
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgDescription($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgDescription($fieldName, $fieldArgName);
    }

    public function getFieldArgDeprecationDescription(string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgDeprecationDescription($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgDeprecationDescription($fieldName, $fieldArgName);
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgDefaultValue($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterSchemaFieldArgTypeModifiers($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
}
