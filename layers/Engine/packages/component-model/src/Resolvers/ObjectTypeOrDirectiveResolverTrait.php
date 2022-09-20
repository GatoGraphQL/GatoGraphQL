<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use stdClass;

trait ObjectTypeOrFieldDirectiveResolverTrait
{
    /**
     * Get the field/directive arguments which have a default value.
     *
     * Set the missing InputObject as {} to give it a chance to set
     * its default input values
     *
     * @param array<string,mixed> $fieldOrDirectiveArgsSchemaDefinition
     * @return array<string,mixed>
     */
    final protected function getFieldOrDirectiveArgumentNameDefaultValues(array $fieldOrDirectiveArgsSchemaDefinition): array
    {
        $fieldOrDirectiveArgNameDefaultValues = [];
        foreach ($fieldOrDirectiveArgsSchemaDefinition as $fieldOrDirectiveSchemaDefinitionArg) {
            /** @var string */
            $fieldOrDirectiveName = $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::NAME];
            if (\array_key_exists(SchemaDefinition::DEFAULT_VALUE, $fieldOrDirectiveSchemaDefinitionArg)) {
                // If it has a default value, set it
                $fieldOrDirectiveArgNameDefaultValues[$fieldOrDirectiveName] = $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::DEFAULT_VALUE];
                continue;
            }
            if (
                // If it is a non-mandatory InputObject, set {}
                // (If it is mandatory, don't set a value as to let the validation fail)
                $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER] instanceof InputObjectTypeResolverInterface
                && !($fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false)
            ) {
                $fieldOrDirectiveArgNameDefaultValues[$fieldOrDirectiveName] = new stdClass();
            }
        }
        return $fieldOrDirectiveArgNameDefaultValues;
    }

    /**
     * Get the mandatory argument names for the field
     *
     * @param array<string,mixed> $fieldOrDirectiveArgsSchemaDefinition
     * @return string[]
     */
    private function getFieldOrDirectiveMandatoryArgumentNames(array $fieldOrDirectiveArgsSchemaDefinition): array
    {
        $mandatoryFieldOrDirectiveArgumentNames = [];
        foreach ($fieldOrDirectiveArgsSchemaDefinition as $fieldOrDirectiveSchemaDefinitionArg) {
            if ($fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false) {
                $mandatoryFieldOrDirectiveArgumentNames[] = $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::NAME];
            }
        }
        return $mandatoryFieldOrDirectiveArgumentNames;
    }

    /**
     * Get the mandatory argument names for the field
     *
     * @param array<string,mixed> $fieldOrDirectiveArgsSchemaDefinition
     * @return string[]
     */
    private function getFieldOrDirectiveMandatoryButNullableArgumentNames(array $fieldOrDirectiveArgsSchemaDefinition): array
    {
        $mandatoryButNullableFieldOrDirectiveArgumentNames = [];
        foreach ($fieldOrDirectiveArgsSchemaDefinition as $fieldOrDirectiveSchemaDefinitionArg) {
            if ($fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::MANDATORY_BUT_NULLABLE] ?? false) {
                $mandatoryButNullableFieldOrDirectiveArgumentNames[] = $fieldOrDirectiveSchemaDefinitionArg[SchemaDefinition::NAME];
            }
        }
        return $mandatoryButNullableFieldOrDirectiveArgumentNames;
    }

    /**
     * @param array<string,mixed> $fieldOrDirectiveArgs
     * @param array<string,mixed> $argumentNameDefaultValues
     * @return array<string,mixed>
     */
    final protected function addDefaultFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgs,
        array $argumentNameDefaultValues,
    ): array {
        foreach ($argumentNameDefaultValues as $argName => $argDefaultValue) {
            if (array_key_exists($argName, $fieldOrDirectiveArgs)) {
                continue;
            }
            $fieldOrDirectiveArgs[$argName] = $argDefaultValue;
        }
        return $fieldOrDirectiveArgs;
    }
}
