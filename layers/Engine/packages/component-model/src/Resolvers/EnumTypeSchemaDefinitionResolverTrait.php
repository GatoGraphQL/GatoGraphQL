<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;

trait EnumTypeSchemaDefinitionResolverTrait
{
    /**
     * Add the enum values in the schema: arrays of enum name,
     * description, deprecated and deprecation description
     */
    protected function doAddSchemaDefinitionEnumValuesForField(
        array &$schemaDefinition,
        EnumTypeResolverInterface $enumTypeResolver,
    ): void {
        $enums = [];
        $enumValues = $enumTypeResolver->getEnumValues();
        $enumValueDeprecationDescriptions = $enumTypeResolver->getEnumValueDeprecationMessages();
        $enumValueDescriptions = $enumTypeResolver->getEnumValueDescriptions();
        $enumName = $enumTypeResolver->getMaybeNamespacedTypeName();
        foreach ($enumValues as $enumValue) {
            $enum = [
                SchemaDefinition::ARGNAME_NAME => $enumValue,
                SchemaDefinition::ARGNAME_ENUM_NAME => $enumName,
            ];
            if ($description = $enumValueDescriptions[$enumValue] ?? null) {
                $enum[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($deprecationDescription = $enumValueDeprecationDescriptions[$enumValue] ?? null) {
                $enum[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                $enum[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            $enums[$enumValue] = $enum;
        }
        $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_VALUES] = $enums;
    }
}
