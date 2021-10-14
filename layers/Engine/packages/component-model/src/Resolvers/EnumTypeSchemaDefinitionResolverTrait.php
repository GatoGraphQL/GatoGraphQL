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
        $enumValueDeprecationMessages = $enumTypeResolver->getEnumValueDeprecationMessages();
        $enumValueDescriptions = $enumTypeResolver->getEnumValueDescriptions();
        foreach ($enumValues as $enumValue) {
            $enum = [
                SchemaDefinition::NAME => $enumValue,
            ];
            if ($description = $enumValueDescriptions[$enumValue] ?? null) {
                $enum[SchemaDefinition::DESCRIPTION] = $description;
            }
            if ($deprecationMessage = $enumValueDeprecationMessages[$enumValue] ?? null) {
                $enum[SchemaDefinition::DEPRECATED] = true;
                $enum[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
            }
            $enums[$enumValue] = $enum;
        }
        $schemaDefinition[SchemaDefinition::ENUM_VALUES] = $enums;
    }
}
