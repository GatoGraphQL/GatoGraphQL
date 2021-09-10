<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;

trait EnumTypeFieldSchemaDefinitionResolverTrait
{
    use EnumTypeSchemaDefinitionResolverTrait;

    protected function getSchemaDefinitionEnumName(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDeprecationDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    protected function getSchemaDefinitionEnumValueDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return null;
    }

    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     */
    protected function addSchemaDefinitionEnumValuesForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        $enumValues = $this->getSchemaDefinitionEnumValues($objectTypeResolver, $fieldName);
        if (!is_null($enumValues)) {
            $enumValueDeprecationDescriptions = $this->getSchemaDefinitionEnumValueDeprecationDescriptions($objectTypeResolver, $fieldName) ?? [];
            $enumValueDescriptions = $this->getSchemaDefinitionEnumValueDescriptions($objectTypeResolver, $fieldName) ?? [];
            $enumName = $this->getSchemaDefinitionEnumName($objectTypeResolver, $fieldName);
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaDefinition,
                $enumValues,
                $enumValueDeprecationDescriptions,
                $enumValueDescriptions,
                $enumName
            );
        }
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        parent::addSchemaDefinitionForField($schemaDefinition, $objectTypeResolver, $fieldName);

        $this->addSchemaDefinitionEnumValuesForField($schemaDefinition, $objectTypeResolver, $fieldName);
    }
}
