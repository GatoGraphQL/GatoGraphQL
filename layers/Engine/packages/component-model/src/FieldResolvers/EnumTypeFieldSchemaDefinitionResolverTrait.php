<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;

trait EnumTypeFieldSchemaDefinitionResolverTrait
{
    use EnumTypeSchemaDefinitionResolverTrait;

    protected function getSchemaDefinitionEnumName(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValues(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDeprecationDescriptions(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDescriptions(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     */
    protected function addSchemaDefinitionEnumValuesForField(array &$schemaDefinition, RelationalTypeResolverInterface $typeResolver, string $fieldName): void
    {
        $enumValues = $this->getSchemaDefinitionEnumValues($typeResolver, $fieldName);
        if (!is_null($enumValues)) {
            $enumValueDeprecationDescriptions = $this->getSchemaDefinitionEnumValueDeprecationDescriptions($typeResolver, $fieldName) ?? [];
            $enumValueDescriptions = $this->getSchemaDefinitionEnumValueDescriptions($typeResolver, $fieldName) ?? [];
            $enumName = $this->getSchemaDefinitionEnumName($typeResolver, $fieldName);
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaDefinition,
                $enumValues,
                $enumValueDeprecationDescriptions,
                $enumValueDescriptions,
                $enumName
            );
        }
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $typeResolver, string $fieldName): void
    {
        parent::addSchemaDefinitionForField($schemaDefinition, $typeResolver, $fieldName);

        $this->addSchemaDefinitionEnumValuesForField($schemaDefinition, $typeResolver, $fieldName);
    }
}
