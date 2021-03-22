<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait FieldOrDirectiveResolverTrait
{
    /**
     * @var array<array|null>
     */
    protected array $enumValueArgumentValidationCache = [];

    protected function maybeValidateNotMissingFieldOrDirectiveArguments(TypeResolverInterface $typeResolver, string $fieldOrDirectiveName, array $fieldOrDirectiveArgs, array $schemaFieldOrDirectiveArgs, string $type): ?string
    {
        if ($mandatoryArgs = SchemaHelpers::getSchemaMandatoryFieldArgs($schemaFieldOrDirectiveArgs)) {
            if (
                $maybeError = $this->validateNotMissingFieldOrDirectiveArguments(
                    SchemaHelpers::getSchemaFieldArgNames($mandatoryArgs),
                    $fieldOrDirectiveName,
                    $fieldOrDirectiveArgs,
                    $type
                )
            ) {
                return $maybeError;
            }
        }
        return null;
    }

    protected function validateNotMissingFieldOrDirectiveArguments(array $fieldOrDirectiveArgumentProperties, string $fieldOrDirectiveName, array $fieldOrDirectiveArgs, string $type): ?string
    {
        if ($missing = SchemaHelpers::getMissingFieldArgs($fieldOrDirectiveArgumentProperties, $fieldOrDirectiveArgs)) {
            $translationAPI = TranslationAPIFacade::getInstance();
            return count($missing) == 1 ?
                sprintf(
                    $translationAPI->__('Argument \'%1$s\' cannot be empty, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $missing[0],
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                ) :
                sprintf(
                    $translationAPI->__('Arguments \'%1$s\' cannot be empty, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    implode($translationAPI->__('\', \''), $missing),
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                );
        }
        return null;
    }

    /**
     * Important: The validations below can only be done if no fieldArg contains a field!
     * That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
     * For instance, this doesn't work: /?query=arrayItem(posts(),3)
     * In that case, the validation will be done inside ->resolveValue(),
     * and will be treated as a $dbError, not a $schemaError
     */
    protected function maybeValidateEnumFieldOrDirectiveArguments(TypeResolverInterface $typeResolver, string $fieldOrDirectiveName, array $fieldOrDirectiveArgs, array $schemaFieldOrDirectiveArgs, string $type): ?array
    {
        if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldOrDirectiveArgs)) {
            // Iterate all the enum types and check that the provided values is one of them, or throw an error
            if ($enumArgs = SchemaHelpers::getSchemaEnumTypeFieldArgs($schemaFieldOrDirectiveArgs)) {
                return $this->validateEnumFieldOrDirectiveArguments(
                    $enumArgs,
                    $fieldOrDirectiveName,
                    $fieldOrDirectiveArgs,
                    $type
                );
            }
        }
        return null;
    }

    protected function validateEnumFieldOrDirectiveArguments(array $enumArgs, string $fieldOrDirectiveName, array $fieldOrDirectiveArgs, string $type): ?array
    {
        $key = serialize($enumArgs) . '|' . $fieldOrDirectiveName . serialize($fieldOrDirectiveArgs);
        if (!isset($this->enumValueArgumentValidationCache[$key])) {
            $this->enumValueArgumentValidationCache[$key] = $this->doValidateEnumFieldOrDirectiveArguments($enumArgs, $fieldOrDirectiveName, $fieldOrDirectiveArgs, $type);
        }
        return $this->enumValueArgumentValidationCache[$key];
    }
    protected function doValidateEnumFieldOrDirectiveArguments(array $enumArgs, string $fieldOrDirectiveName, array $fieldOrDirectiveArgs, string $type): ?array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errors = $deprecations = [];
        $fieldOrDirectiveArgumentNames = SchemaHelpers::getSchemaFieldArgNames($enumArgs);
        $schemaFieldArgumentEnumValueDefinitions = SchemaHelpers::getSchemaFieldArgEnumValueDefinitions($enumArgs);
        for ($i = 0; $i < count($fieldOrDirectiveArgumentNames); $i++) {
            $fieldOrDirectiveArgumentName = $fieldOrDirectiveArgumentNames[$i];
            $fieldOrDirectiveArgumentValue = $fieldOrDirectiveArgs[$fieldOrDirectiveArgumentName] ?? null;
            if (!is_null($fieldOrDirectiveArgumentValue)) {
                // Each fieldArgumentEnumValue is an array with item "name" for sure, and maybe also "description", "deprecated" and "deprecationDescription"
                $schemaFieldOrDirectiveArgumentEnumValues = $schemaFieldArgumentEnumValueDefinitions[$fieldOrDirectiveArgumentName];
                $fieldOrDirectiveArgumentValueDefinition = $schemaFieldOrDirectiveArgumentEnumValues[$fieldOrDirectiveArgumentValue];
                if (is_null($fieldOrDirectiveArgumentValueDefinition)) {
                    // Remove deprecated ones and extract their names
                    $fieldOrDirectiveArgumentEnumValues = SchemaHelpers::removeDeprecatedEnumValuesFromSchemaDefinition($schemaFieldOrDirectiveArgumentEnumValues);
                    $fieldOrDirectiveArgumentEnumValues = array_keys($fieldOrDirectiveArgumentEnumValues);
                    $errors[] = sprintf(
                        $translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                        $fieldOrDirectiveArgumentValue,
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName,
                        implode($translationAPI->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                    );
                } elseif ($fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                    // Check if this enumValue is deprecated
                    $deprecations[] = sprintf(
                        $translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is deprecated: \'%5$s\'', 'component-model'),
                        $fieldOrDirectiveArgumentValue,
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName,
                        $fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION]
                    );
                }
            }
        }
        // if ($errors) {
        //     return implode($translationAPI->__('. '), $errors);
        // }
        // Array of 2 items: errors and deprecations
        if ($errors || $deprecations) {
            return [
                $errors ? implode($translationAPI->__('. '), $errors) : null,
                $deprecations ? implode($translationAPI->__('. '), $deprecations) : null,
            ];
        }
        return null;
    }
}
