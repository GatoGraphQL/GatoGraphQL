<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait FieldOrDirectiveResolverTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;

    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function autowireFieldOrDirectiveResolverTrait(
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->translationAPI = $translationAPI;
    }

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
                    array_keys($mandatoryArgs),
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

    private function doValidateNotMissingFieldOrDirectiveArguments(
        array $mandatoryFieldOrDirectiveArgNames,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?string {
        if ($missing = SchemaHelpers::getMissingFieldArgs($mandatoryFieldOrDirectiveArgNames, $fieldOrDirectiveArgs)) {
            $treatUndefinedFieldOrDirectiveArgsAsErrors = ComponentConfiguration::treatUndefinedFieldOrDirectiveArgsAsErrors();
            $errorMessage = count($missing) == 1 ?
                sprintf(
                    $this->translationAPI->__('Argument \'%1$s\' cannot be empty', 'component-model'),
                    $missing[0]
                ) :
                sprintf(
                    $this->translationAPI->__('Arguments \'%1$s\' cannot be empty', 'component-model'),
                    implode($this->translationAPI->__('\', \''), $missing)
                );
            if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                return $errorMessage;
            }
            return count($missing) == 1 ?
                sprintf(
                    $this->translationAPI->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                ) :
                sprintf(
                    $this->translationAPI->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                );
        }
        return null;
    }

    /**
     * The validations below can only be done if no fieldArg or directiveArg contains a field!
     * That is because this is a schema error, so we still don't have the $object against which to resolve the field
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

    protected function validateEnumFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        // Iterate all the enum types and check that the provided values is one of them, or throw an error
        if ($enumTypeFieldOrDirectiveArgsSchemaDefinition = SchemaHelpers::getEnumTypeFieldOrDirectiveArgsSchemaDefinition($fieldOrDirectiveArgsSchemaDefinition)) {
            [$maybeErrors] = $this->doValidateEnumFieldOrDirectiveArgumentsOrGetFromCache(
                $enumTypeFieldOrDirectiveArgsSchemaDefinition,
                $fieldOrDirectiveName,
                $fieldOrDirectiveArgs,
                $type
            );
            return $maybeErrors;
        }
        return [];
    }

    /**
     * Deprecations for the field/directive args.
     *
     * Watch out! The GraphQL spec does not include deprecations for arguments,
     * only for fields and enum values, but here it is added nevertheless.
     * This message is shown on runtime when executing a query with a deprecated field,
     * but it's not shown when doing introspection.
     *
     * It is executed only when enabled by configuration (by default it is not)
     *
     * @see https://spec.graphql.org/draft/#sec-Schema-Introspection.Schema-Introspection-Schema
     */
    protected function maybeGetFieldOrDirectiveArgumentDeprecations(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        if (ComponentConfiguration::enableFieldOrDirectiveArgumentDeprecations()) {
            $fieldOrDirectiveDeprecationMessages = [];
            foreach ($fieldOrDirectiveArgs as $fieldOrDirectiveArgName => $directiveArgValue) {
                $fieldOrDirectiveArgSchemaDefinition = $fieldOrDirectiveArgsSchemaDefinition[$fieldOrDirectiveArgName] ?? [];
                if ($fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::DEPRECATED] ?? null) {
                    $fieldOrDirectiveDeprecationMessages[] = sprintf(
                        $this->translationAPI->__('Argument \'%s\' in %s \'%s\' is deprecated: %s', 'component-model'),
                        $fieldOrDirectiveArgName,
                        $type,
                        $fieldOrDirectiveName,
                        $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] ?? ''
                    );
                }
            }
            return $fieldOrDirectiveDeprecationMessages;
        }
        return [];
    }

    protected function getEnumFieldOrDirectiveArgumentDeprecations(
        array $fieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        // Iterate all the enum types and check that the provided values is one of them, or throw an error
        if ($enumTypeFieldOrDirectiveArgsSchemaDefinition = SchemaHelpers::getEnumTypeFieldOrDirectiveArgsSchemaDefinition($fieldOrDirectiveArgsSchemaDefinition)) {
            [$maybeErrors, $maybeDeprecations] = $this->doValidateEnumFieldOrDirectiveArgumentsOrGetFromCache(
                $enumTypeFieldOrDirectiveArgsSchemaDefinition,
                $fieldOrDirectiveName,
                $fieldOrDirectiveArgs,
                $type
            );
            return $maybeDeprecations;
        }
        return [];
    }

    /**
     * @return array[] 2 items: [0]: array of errors, [1]: array of deprecations
     */
    private function doValidateEnumFieldOrDirectiveArgumentsOrGetFromCache(
        array $enumTypeFieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        // Remove the resolver before serialization, or it throws an error
        $serializableEnumTypeFieldOrDirectiveArgsSchemaDefinition = array_map(
            function (array $enumTypeFieldOrDirectiveArgSchemaDefinition): array {
                unset($enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER]);
                return $enumTypeFieldOrDirectiveArgSchemaDefinition;
            },
            $enumTypeFieldOrDirectiveArgsSchemaDefinition
        );
        $key = serialize($serializableEnumTypeFieldOrDirectiveArgsSchemaDefinition) . '|' . $fieldOrDirectiveName . serialize($fieldOrDirectiveArgs);
        if (!isset($this->enumValueArgumentValidationCache[$key])) {
            $this->enumValueArgumentValidationCache[$key] = $this->doValidateEnumFieldOrDirectiveArguments($enumTypeFieldOrDirectiveArgsSchemaDefinition, $fieldOrDirectiveName, $fieldOrDirectiveArgs, $type);
        }
        return $this->enumValueArgumentValidationCache[$key];
    }

    /**
     * @return array[] 2 items: [0]: array of errors, [1]: array of deprecations
     */
    private function doValidateEnumFieldOrDirectiveArguments(
        array $enumTypeFieldOrDirectiveArgsSchemaDefinition,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        $errors = $deprecations = [];
        $fieldOrDirectiveArgumentNames = array_keys($enumTypeFieldOrDirectiveArgsSchemaDefinition);
        $schemaFieldArgumentEnumValueDefinitions = SchemaHelpers::getSchemaFieldArgEnumValueDefinitions($enumTypeFieldOrDirectiveArgsSchemaDefinition);
        foreach ($fieldOrDirectiveArgumentNames as $fieldOrDirectiveArgumentName) {
            $fieldOrDirectiveArgumentValue = $fieldOrDirectiveArgs[$fieldOrDirectiveArgumentName] ?? null;
            if ($fieldOrDirectiveArgumentValue === null) {
                continue;
            }
            // Check if it's an array or not from the schema definition
            $enumTypeFieldOrDirectiveArgSchemaDefinition = $enumTypeFieldOrDirectiveArgsSchemaDefinition[$fieldOrDirectiveArgumentName];
            $enumTypeFieldOrDirectiveArgIsArrayOfArrays = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
            $enumTypeFieldOrDirectiveArgIsArray = $enumTypeFieldOrDirectiveArgSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false;
            $schemaFieldOrDirectiveArgumentEnumValues = $schemaFieldArgumentEnumValueDefinitions[$fieldOrDirectiveArgumentName] ?? [];

            /**
             * Pass all the enum values to be validated, as a list.
             * Possibilities:
             *   1. Single item => [item]
             *   2. Array => Array
             *   3. Array of arrays => flatten into array
             */
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
        // Array of 2 items: errors and deprecations
        return [$errors, $deprecations];
    }

    private function doValidateEnumFieldOrDirectiveArgumentsItem(
        array &$errors,
        array &$deprecations,
        array $schemaFieldOrDirectiveArgumentEnumValues,
        array $fieldOrDirectiveArgumentValueItems,
        string $fieldOrDirectiveArgumentName,
        string $fieldOrDirectiveName,
        string $type
    ): void {
        $errorItems = $deprecationItems = [];
        foreach ($fieldOrDirectiveArgumentValueItems as $fieldOrDirectiveArgumentValueItem) {
            $fieldOrDirectiveArgumentValueDefinition = $schemaFieldOrDirectiveArgumentEnumValues[$fieldOrDirectiveArgumentValueItem] ?? null;
            if ($fieldOrDirectiveArgumentValueDefinition === null) {
                // Remove deprecated ones and extract their names
                $errorItems[] = $fieldOrDirectiveArgumentValueItem;
            } elseif ($fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::DEPRECATED] ?? null) {
                // Check if this enumValue is deprecated
                $deprecationItems[$fieldOrDirectiveArgumentValueItem] = $fieldOrDirectiveArgumentValueDefinition[SchemaDefinition::DEPRECATION_MESSAGE];
            }
        }
        if ($errorItems) {
            $fieldOrDirectiveArgumentEnumValues = SchemaHelpers::removeDeprecatedEnumValuesFromSchemaDefinition($schemaFieldOrDirectiveArgumentEnumValues);
            $fieldOrDirectiveArgumentEnumValues = array_keys($fieldOrDirectiveArgumentEnumValues);
            if (count($errorItems) === 1) {
                $errors[] = sprintf(
                    $this->translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($this->translationAPI->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($this->translationAPI->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            } else {
                $errors[] = sprintf(
                    $this->translationAPI->__('Values \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' are not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($this->translationAPI->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($this->translationAPI->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            }
        }
        foreach ($deprecationItems as $fieldOrDirectiveArgumentValueItem => $deprecationItemDescription) {
            $deprecations[] = sprintf(
                $this->translationAPI->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is deprecated: \'%5$s\'', 'component-model'),
                $fieldOrDirectiveArgumentValueItem,
                $fieldOrDirectiveArgumentName,
                $type == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                $fieldOrDirectiveName,
                $deprecationItemDescription
            );
        }
    }
}
