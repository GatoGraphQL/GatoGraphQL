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
                SchemaDefinition::NAME => $enumValue,
                SchemaDefinition::ENUM_NAME => $enumName,
            ];
            if ($description = $enumValueDescriptions[$enumValue] ?? null) {
                $enum[SchemaDefinition::DESCRIPTION] = $description;
            }
            if ($deprecationDescription = $enumValueDeprecationDescriptions[$enumValue] ?? null) {
                $enum[SchemaDefinition::DEPRECATED] = true;
                $enum[SchemaDefinition::DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            $enums[$enumValue] = $enum;
        }
        $schemaDefinition[SchemaDefinition::ENUM_VALUES] = $enums;
    }
}
