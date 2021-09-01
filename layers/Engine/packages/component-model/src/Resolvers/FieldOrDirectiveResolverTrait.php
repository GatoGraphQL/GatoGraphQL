<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;

trait FieldOrDirectiveResolverTrait
{
    /**
     * @var array<array|null>
     */
    protected array $enumValueArgumentValidationCache = [];

    protected function validateNotMissingFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?string {
        if ($mandatoryArgs = SchemaHelpers::getSchemaMandatoryFieldOrDirectiveArgs($fieldOrDirectiveArgsSchemaDefinition)) {
            if (
                $maybeError = $this->doValidateNotMissingFieldOrDirectiveArguments(
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

    protected function doValidateNotMissingFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgumentProperties,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?string {
        if ($missing = SchemaHelpers::getMissingFieldArgs($fieldOrDirectiveArgumentProperties, $fieldOrDirectiveArgs)) {
            $translationAPI = TranslationAPIFacade::getInstance();
            $treatUndefinedFieldOrDirectiveArgsAsErrors = ComponentConfiguration::treatUndefinedFieldOrDirectiveArgsAsErrors();
            $errorMessage = count($missing) == 1 ?
                sprintf(
                    $translationAPI->__('Argument \'%1$s\' cannot be empty', 'component-model'),
                    $missing[0]
                ) :
                sprintf(
                    $translationAPI->__('Arguments \'%1$s\' cannot be empty', 'component-model'),
                    implode($translationAPI->__('\', \''), $missing)
                );
            if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                return $errorMessage;
            }
            return count($missing) == 1 ?
                sprintf(
                    $translationAPI->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                ) :
                sprintf(
                    $translationAPI->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                );
        }
        return null;
    }

    /**
     * The validations below can only be done if no fieldArg or directiveArg contains a field!
     * That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
     * For instance, this doesn't work: /?query=arrayItem(posts(),3)
     * In that case, the validation will be done inside ->resolveValue(),
     * and will be treated as a $dbError, not a $schemaError.
     *
     * Same with expressions, as when calling `getSelfProp(%self%, "posts")`.
     *
     * But no need with variables, because by now they will have been replaced with the actual value.
     */
    protected function canValidateFieldOrDirectiveArgumentsWithValuesForSchema(array $fieldOrDirectiveArgs): bool
    {
        return !FieldQueryUtils::isAnyFieldArgumentValueAFieldOrExpression($fieldOrDirectiveArgs);
    }

    protected function validateArrayTypeFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?string {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errors = [];
        $fieldOrDirectiveArgumentNames = SchemaHelpers::getSchemaFieldArgNames($fieldOrDirectiveArgsSchemaDefinition);
        for ($i = 0; $i < count($fieldOrDirectiveArgumentNames); $i++) {
            $fieldOrDirectiveArgumentName = $fieldOrDirectiveArgumentNames[$i];
            $fieldOrDirectiveArgumentValue = $fieldOrDirectiveArgs[$fieldOrDirectiveArgumentName] ?? null;
            if ($fieldOrDirectiveArgumentValue !== null) {
                // Check if it's an array or not from the schema definition
                $fieldOrDirectiveArgSchemaDefinition = $fieldOrDirectiveArgsSchemaDefinition[$fieldOrDirectiveArgumentName];
                /**
                 * This value will not be used with GraphQL, but can be used by PoP.
                 *
                 * While GraphQL has a strong type system, PoP takes a more lenient approach,
                 * enabling fields to maybe be an array, maybe not.
                 *
                 * Eg: `echo(object: ...)` will print back whatever provided,
                 * whether `String` or `[String]`. Its input is `Mixed`, which can comprise
                 * an `Object`, so it could be provided as an array, or also `String`, which
                 * will not be an array.
                 *
                 * Whenever the value may be an array, the server will skip those validations
                 * to check if an input is array or not (and throw an error).
                 */
                $fieldOrDirectiveArgType = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_TYPE];
                $fieldOrDirectiveArgMayBeArray = in_array($fieldOrDirectiveArgType, [
                    SchemaDefinition::TYPE_INPUT_OBJECT,
                    SchemaDefinition::TYPE_OBJECT,
                    SchemaDefinition::TYPE_MIXED,
                ]);
                // If the value may be array, may be not, then there's nothing to validate
                if ($fieldOrDirectiveArgMayBeArray) {
                    continue;
                }
                $fieldOrDirectiveArgIsArray = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
                $fieldOrDirectiveArgNonNullArrayItems = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
                $fieldOrDirectiveArgIsArrayOfArrays = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
                $fieldOrDirectiveArgNonNullArrayOfArraysItems = $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;
                if (
                    !$fieldOrDirectiveArgIsArray
                    && is_array($fieldOrDirectiveArgumentValue)
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must not be an array', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                } elseif (
                    $fieldOrDirectiveArgIsArray
                    && !is_array($fieldOrDirectiveArgumentValue)
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must be an array', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                } elseif (
                    $fieldOrDirectiveArgNonNullArrayItems
                    && is_array($fieldOrDirectiveArgumentValue)
                    && array_filter(
                        $fieldOrDirectiveArgumentValue,
                        fn ($arrayItem) => $arrayItem === null
                    )
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The array for argument \'%1$s\' in %2$s \'%3$s\' cannot have `null` values', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                } elseif (
                    !$fieldOrDirectiveArgIsArrayOfArrays
                    && is_array($fieldOrDirectiveArgumentValue)
                    // Check if any element is an array
                    && array_filter(
                        $fieldOrDirectiveArgumentValue,
                        fn (mixed $arrayItem) => is_array($arrayItem)
                    )
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The array for argument \'%1$s\' in %2$s \'%3$s\' must not contain arrays', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                } elseif (
                    $fieldOrDirectiveArgIsArrayOfArrays
                    && is_array($fieldOrDirectiveArgumentValue)
                    // Check if any element is not an array
                    && array_filter(
                        $fieldOrDirectiveArgumentValue,
                        // `null` could be accepted as an array! (Validation against null comes next)
                        fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                    )
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must be an array of arrays', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                } elseif (
                    $fieldOrDirectiveArgNonNullArrayOfArraysItems
                    && is_array($fieldOrDirectiveArgumentValue)
                    && array_filter(
                        $fieldOrDirectiveArgumentValue,
                        fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                            $arrayItem,
                            fn ($arrayItemItem) => $arrayItemItem === null
                        ) !== [],
                    )
                ) {
                    $errors[] = sprintf(
                        $translationAPI->__('The array of arrays for argument \'%1$s\' in %2$s \'%3$s\' cannot have `null` values', 'component-model'),
                        $fieldOrDirectiveArgumentName,
                        $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                        $fieldOrDirectiveName
                    );
                }
            }
        }
        if ($errors) {
            return implode($translationAPI->__('. '), $errors);
        }
        return null;
    }

    protected function validateEnumFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?array {
        // Iterate all the enum types and check that the provided values is one of them, or throw an error
        if ($enumTypeFieldOrDirectiveArgsSchemaDefinition = SchemaHelpers::getEnumTypeFieldOrDirectiveArgsSchemaDefinition($fieldOrDirectiveArgsSchemaDefinition)) {
            return $this->doValidateEnumFieldOrDirectiveArgumentsOrGetFromCache(
                $enumTypeFieldOrDirectiveArgsSchemaDefinition,
                $fieldOrDirectiveName,
                $fieldOrDirectiveArgs,
                $type
            );
        }
        return null;
    }

    protected function doValidateEnumFieldOrDirectiveArgumentsOrGetFromCache(
        array $enumTypeFieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?array {
        $key = serialize($enumTypeFieldOrDirectiveArgsSchemaDefinition) . '|' . $fieldOrDirectiveName . serialize($fieldOrDirectiveArgs);
        if (!isset($this->enumValueArgumentValidationCache[$key])) {
            $this->enumValueArgumentValidationCache[$key] = $this->doValidateEnumFieldOrDirectiveArguments($enumTypeFieldOrDirectiveArgsSchemaDefinition, $fieldOrDirectiveName, $fieldOrDirectiveArgs, $type);
        }
        return $this->enumValueArgumentValidationCache[$key];
    }
    protected function doValidateEnumFieldOrDirectiveArguments(
        array $enumTypeFieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?array {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errors = $deprecations = [];
        $fieldOrDirectiveArgumentNames = SchemaHelpers::getSchemaFieldArgNames($enumTypeFieldOrDirectiveArgsSchemaDefinition);
        $schemaFieldArgumentEnumValueDefinitions = SchemaHelpers::getSchemaFieldArgEnumValueDefinitions($enumTypeFieldOrDirectiveArgsSchemaDefinition);
        for ($i = 0; $i < count($fieldOrDirectiveArgumentNames); $i++) {
            $fieldOrDirectiveArgumentName = $fieldOrDirectiveArgumentNames[$i];
            $fieldOrDirectiveArgumentValue = $fieldOrDirectiveArgs[$fieldOrDirectiveArgumentName] ?? null;
            if ($fieldOrDirectiveArgumentValue !== null) {
                // Check if it's an array or not from the schema definition
                $enumTypeFieldOrDirectiveArgSchemaDefinition = $enumTypeFieldOrDirectiveArgsSchemaDefinition[$fieldOrDirectiveArgumentName];
                // If the value may be array, may be not, then there's nothing to validate
                /**
                 * This value will not be used with GraphQL, but can be used by PoP.
                 *
                 * While GraphQL has a strong type system, PoP takes a more lenient approach,
                 * enabling fields to maybe be an array, maybe not.
                 *
                 * Eg: `echo(value: ...)` will print back whatever provided,
                 * whether `String` or `[String]`. Its input is `Mixed`, which can comprise
                 * an `Object`, so it could be provided as an array, or also `String`, which
                 * will not be an array.
                 *
                 * Whenever the value may be an array, the server will skip those validations
                 * to check if an input is array or not (and throw an error).
                 */
                $enumTypeFieldOrDirectiveArgType = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_TYPE];
                $enumTypeFieldOrDirectiveArgMayBeArray = in_array($enumTypeFieldOrDirectiveArgType, [
                    SchemaDefinition::TYPE_INPUT_OBJECT,
                    SchemaDefinition::TYPE_OBJECT,
                    SchemaDefinition::TYPE_MIXED,
                ]);
                // If setting the "array of arrays" flag, there's no need to set the "array" flag
                $enumTypeFieldOrDirectiveArgIsArrayOfArrays = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
                $enumTypeFieldOrDirectiveArgIsArray = ($enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false)
                    || $enumTypeFieldOrDirectiveArgIsArrayOfArrays;
                $enumTypeFieldOrDirectiveArgNonNullArrayItems = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
                $enumTypeFieldOrDirectiveArgNonNullArrayOfArraysItems = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;
                // Each fieldArgumentEnumValue is an array with item "name" for sure, and maybe also "description", "deprecated" and "deprecationDescription"
                $schemaFieldOrDirectiveArgumentEnumValues = $schemaFieldArgumentEnumValueDefinitions[$fieldOrDirectiveArgumentName];
                if (!$enumTypeFieldOrDirectiveArgMayBeArray) {
                    if (
                        !$enumTypeFieldOrDirectiveArgIsArray
                        && is_array($fieldOrDirectiveArgumentValue)
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must not be an array', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                    if (
                        $enumTypeFieldOrDirectiveArgIsArray
                        && !is_array($fieldOrDirectiveArgumentValue)
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must be an array', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                    if (
                        $enumTypeFieldOrDirectiveArgIsArray
                        && is_array($fieldOrDirectiveArgumentValue)
                        && $enumTypeFieldOrDirectiveArgNonNullArrayItems
                        && array_filter(
                            $fieldOrDirectiveArgumentValue,
                            fn ($arrayItem) => $arrayItem === null
                        )
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The array for argument \'%1$s\' in %2$s \'%3$s\' cannot have `null` values', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                    if (
                        !$enumTypeFieldOrDirectiveArgIsArrayOfArrays
                        && is_array($fieldOrDirectiveArgumentValue)
                        && array_filter(
                            $fieldOrDirectiveArgumentValue,
                            fn (mixed $arrayItem) => is_array($arrayItem)
                        )
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The array for argument \'%1$s\' in %2$s \'%3$s\' must not contain arrays', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                    if (
                        $enumTypeFieldOrDirectiveArgIsArrayOfArrays
                        && is_array($fieldOrDirectiveArgumentValue)
                        && array_filter(
                            $fieldOrDirectiveArgumentValue,
                            // `null` could be accepted as an array! (Validation against null comes next)
                            fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                        )
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The value for argument \'%1$s\' in %2$s \'%3$s\' must be an array of arrays', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                    if (
                        $enumTypeFieldOrDirectiveArgIsArrayOfArrays
                        && is_array($fieldOrDirectiveArgumentValue)
                        && $enumTypeFieldOrDirectiveArgNonNullArrayOfArraysItems
                        && array_filter(
                            $fieldOrDirectiveArgumentValue,
                            fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                                $arrayItem,
                                fn ($arrayItemItem) => $arrayItemItem === null
                            ) !== [],
                        )
                    ) {
                        $errors[] = sprintf(
                            $translationAPI->__('The array of arrays for argument \'%1$s\' in %2$s \'%3$s\' cannot have `null` values', 'component-model'),
                            $fieldOrDirectiveArgumentName,
                            $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                            $fieldOrDirectiveName
                        );
                        continue;
                    }
                }
                // Pass all the enum values to be validated, as a list.
                // Possibilities:
                //   1. Single item => [item]
                //   2. Array => Array
                //   3. Array of arrays => flatten into array
                if ($enumTypeFieldOrDirectiveArgIsArrayOfArrays) {
                    $fieldOrDirectiveArgumentValueEnums = array_unique(GeneralUtils::arrayFlatten($fieldOrDirectiveArgumentValue));
                } elseif ($enumTypeFieldOrDirectiveArgIsArray) {
                    $fieldOrDirectiveArgumentValueEnums = $fieldOrDirectiveArgumentValue;
                } else {
                    $fieldOrDirectiveArgumentValueEnums = [$fieldOrDirectiveArgumentValue];
                }
                $this->doValidateEnumFieldOrDirectiveArgumentsItem(
                    $errors,
                    $deprecations,
                    $schemaFieldOrDirectiveArgumentEnumValues,
                    $fieldOrDirectiveArgumentValueEnums,
                    $fieldOrDirectiveArgumentName,
                    $fieldOrDirectiveName,
                    $type,
                );
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

    protected function doValidateEnumFieldOrDirectiveArgumentsItem(
        array &$errors,
        array &$deprecations,
        array $schemaFieldOrDirectiveArgumentEnumValues,
        array $fieldOrDirectiveArgumentValueItems,
        string $fieldOrDirectiveArgumentName,
        string $fieldOrDirectiveName,
        string $type
    ): void {
        $translationAPI = TranslationAPIFacade::getInstance();
        $errorItems = $deprecationItems = [];
        foreach ($fieldOrDirectiveArgumentValueItems as $fieldOrDirectiveArgumentValueItem) {
            $fieldOrDirectiveArgumentValueDefinition = $schemaFieldOrDirectiveArgumentEnumValues[$fieldOrDirectiveArgumentValueItem] ?? null;
            if ($fieldOrDirectiveArgumentValueDefinition === null) {
                // Remove deprecated ones and extract their names
                $errorItems[] = $fieldOrDirectiveArgumentValueItem;
            } elseif ($fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                // Check if this enumValue is deprecated
                $deprecationItems[$fieldOrDirectiveArgumentValueItem] = $fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION];
            }
        }
        if ($errorItems) {
            $fieldOrDirectiveArgumentEnumValues = SchemaHelpers::removeDeprecatedEnumValuesFromSchemaDefinition($schemaFieldOrDirectiveArgumentEnumValues);
            $fieldOrDirectiveArgumentEnumValues = array_keys($fieldOrDirectiveArgumentEnumValues);
            if (count($errorItems) === 1) {
                $errors[] = sprintf(
                    $translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($translationAPI->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($translationAPI->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            } else {
                $errors[] = sprintf(
                    $translationAPI->__('Values \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' are not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($translationAPI->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($translationAPI->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            }
        }
        foreach ($deprecationItems as $fieldOrDirectiveArgumentValueItem => $deprecationItemDescription) {
            $deprecations[] = sprintf(
                $translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is deprecated: \'%5$s\'', 'component-model'),
                $fieldOrDirectiveArgumentValueItem,
                $fieldOrDirectiveArgumentName,
                $type == ResolverTypes::FIELD ? $translationAPI->__('field', 'component-model') : $translationAPI->__('directive', 'component-model'),
                $fieldOrDirectiveName,
                $deprecationItemDescription
            );
        }
    }
}
