<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait EnumTypeSchemaDefinitionResolverTrait
{
    /**
     * Add the enum values in the schema: arrays of enum name,
     * description, deprecated and deprecation description
     */
    protected function doAddSchemaDefinitionEnumValuesForField(
        array &$schemaDefinition,
        array $enumValues,
        array $enumValueDeprecationDescriptions,
        array $enumValueDescriptions,
        ?string $enumName
    ): void {
        $enums = [];
        foreach ($enumValues as $enumValue) {
            $enum = [
                SchemaDefinition::ARGNAME_NAME => $enumValue,
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
        // Indicate the unique name, to unify all types to the same Enum
        if ($enumName) {
            $schemaDefinition[SchemaDefinition::ARGNAME_ENUM_NAME] = $enumName;
        }
    }
}
