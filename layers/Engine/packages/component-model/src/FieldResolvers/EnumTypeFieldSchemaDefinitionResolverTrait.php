<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;

trait EnumTypeFieldSchemaDefinitionResolverTrait
{
    use EnumTypeSchemaDefinitionResolverTrait;

    protected function getSchemaDefinitionEnumName(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValues(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDeprecationDescriptions(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDescriptions(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     *
     * @param array $schemaDefinition
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return void
     */
    protected function addSchemaDefinitionEnumValuesForField(array &$schemaDefinition, TypeResolverInterface $typeResolver, string $fieldName): void
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

    public function addSchemaDefinitionForField(array &$schemaDefinition, TypeResolverInterface $typeResolver, string $fieldName): void
    {
        parent::addSchemaDefinitionForField($schemaDefinition, $typeResolver, $fieldName);

        $this->addSchemaDefinitionEnumValuesForField($schemaDefinition, $typeResolver, $fieldName);
    }
}
