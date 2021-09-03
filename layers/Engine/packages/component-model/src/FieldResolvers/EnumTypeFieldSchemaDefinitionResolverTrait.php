<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;

trait EnumTypeFieldSchemaDefinitionResolverTrait
{
    use EnumTypeSchemaDefinitionResolverTrait;

    protected function getSchemaDefinitionEnumName(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDeprecationDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     */
    protected function addSchemaDefinitionEnumValuesForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        $enumValues = $this->getSchemaDefinitionEnumValues($relationalTypeResolver, $fieldName);
        if (!is_null($enumValues)) {
            $enumValueDeprecationDescriptions = $this->getSchemaDefinitionEnumValueDeprecationDescriptions($relationalTypeResolver, $fieldName) ?? [];
            $enumValueDescriptions = $this->getSchemaDefinitionEnumValueDescriptions($relationalTypeResolver, $fieldName) ?? [];
            $enumName = $this->getSchemaDefinitionEnumName($relationalTypeResolver, $fieldName);
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaDefinition,
                $enumValues,
                $enumValueDeprecationDescriptions,
                $enumValueDescriptions,
                $enumName
            );
        }
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        parent::addSchemaDefinitionForField($schemaDefinition, $relationalTypeResolver, $fieldName);

        $this->addSchemaDefinitionEnumValuesForField($schemaDefinition, $relationalTypeResolver, $fieldName);
    }
}
