<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait FieldOrDirectiveResolverTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use BasicServiceTrait;

    /**
     * @var array<array|null>
     */
    protected array $enumValueArgumentValidationCache = [];

    /**
     * Validate that if the key is missing or is `null`,
     * but not if the value is empty such as '""' or [],
     * because empty values could be allowed.
     *
     * Eg: `setTagsOnPost(tags:[])` where `tags` is mandatory
     */
    private function validateNotMissingFieldOrDirectiveArguments(
        array $mandatoryFieldOrDirectiveArgNames,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): ?string {
        $missing = array_values(array_filter(
            $mandatoryFieldOrDirectiveArgNames,
            fn (string $fieldArgName) => !isset($fieldOrDirectiveArgs[$fieldArgName])
        ));
        if ($missing !== []) {
            $treatUndefinedFieldOrDirectiveArgsAsErrors = ComponentConfiguration::treatUndefinedFieldOrDirectiveArgsAsErrors();
            $errorMessage = count($missing) == 1 ?
                sprintf(
                    $this->getTranslationAPI()->__('Argument \'%1$s\' cannot be empty', 'component-model'),
                    $missing[0]
                ) :
                sprintf(
                    $this->getTranslationAPI()->__('Arguments \'%1$s\' cannot be empty', 'component-model'),
                    implode($this->getTranslationAPI()->__('\', \''), $missing)
                );
            if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                return $errorMessage;
            }
            return count($missing) == 1 ?
                sprintf(
                    $this->getTranslationAPI()->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
                    $fieldOrDirectiveName
                ) :
                sprintf(
                    $this->getTranslationAPI()->__('%s, so %2$s \'%3$s\' has been ignored', 'component-model'),
                    $errorMessage,
                    $type == ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
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

    /**
     * @param array $enumDirectiveArgNameTypeResolvers array<string, EnumTypeResolverInterface>
     * @param array $enumDirectiveArgNamesIsArrayOfArrays array<string, bool>
     * @param array $enumDirectiveArgNamesIsArray array<string, bool>
     * @return array[] 2 items: [0]: array of errors, [1]: array of deprecations
     */
    private function validateEnumFieldOrDirectiveArguments(
        array $enumDirectiveArgNameTypeResolvers,
        array $enumDirectiveArgNamesIsArrayOfArrays,
        array $enumDirectiveArgNamesIsArray,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        $enumFieldOrDirectiveArgs = array_intersect_key($fieldOrDirectiveArgs, $enumDirectiveArgNameTypeResolvers);
        $key = $fieldOrDirectiveName . '|' . implode(',', $enumFieldOrDirectiveArgs);
        if (!isset($this->enumValueArgumentValidationCache[$key])) {
            $this->enumValueArgumentValidationCache[$key] = $this->doValidateEnumFieldOrDirectiveArguments(
                $enumDirectiveArgNameTypeResolvers,
                $enumDirectiveArgNamesIsArrayOfArrays,
                $enumDirectiveArgNamesIsArray,
                $fieldOrDirectiveName,
                $fieldOrDirectiveArgs,
                $type,
            );
        }
        return $this->enumValueArgumentValidationCache[$key];
    }

    /**
     * @param array $enumDirectiveArgNameTypeResolvers array<string, EnumTypeResolverInterface>
     * @param array $enumDirectiveArgNamesIsArrayOfArrays array<string, bool>
     * @param array $enumDirectiveArgNamesIsArray array<string, bool>
     * @return array[] 2 items: [0]: array of errors, [1]: array of deprecations
     */
    private function doValidateEnumFieldOrDirectiveArguments(
        array $enumDirectiveArgNameTypeResolvers,
        array $enumDirectiveArgNamesIsArrayOfArrays,
        array $enumDirectiveArgNamesIsArray,
        string $fieldOrDirectiveName,
        array $fieldOrDirectiveArgs,
        string $type
    ): array {
        $errors = $deprecations = [];
        foreach (array_keys($enumDirectiveArgNameTypeResolvers) as $fieldOrDirectiveArgumentName) {
            $fieldOrDirectiveArgumentValue = $fieldOrDirectiveArgs[$fieldOrDirectiveArgumentName] ?? null;
            if ($fieldOrDirectiveArgumentValue === null) {
                continue;
            }
            $enumTypeFieldOrDirectiveArgIsArrayOfArrays = $enumDirectiveArgNamesIsArrayOfArrays[$fieldOrDirectiveArgumentName];
            $enumTypeFieldOrDirectiveArgIsArray = $enumDirectiveArgNamesIsArray[$fieldOrDirectiveArgumentName];
            $fieldOrDirectiveArgumentEnumTypeResolver = $enumDirectiveArgNameTypeResolvers[$fieldOrDirectiveArgumentName];

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
                $fieldOrDirectiveArgumentEnumTypeResolver,
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
        EnumTypeResolverInterface $fieldOrDirectiveArgumentEnumTypeResolver,
        array $fieldOrDirectiveArgumentValueItems,
        string $fieldOrDirectiveArgumentName,
        string $fieldOrDirectiveName,
        string $type
    ): void {
        $errorItems = $deprecationItems = [];
        $schemaFieldOrDirectiveArgumentEnumTypeValues = $fieldOrDirectiveArgumentEnumTypeResolver->getEnumValues();
        $schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessages = $fieldOrDirectiveArgumentEnumTypeResolver->getEnumValueDeprecationMessages();
        foreach ($fieldOrDirectiveArgumentValueItems as $fieldOrDirectiveArgumentValueItem) {
            if (!in_array($fieldOrDirectiveArgumentValueItem, $schemaFieldOrDirectiveArgumentEnumTypeValues)) {
                // Remove deprecated ones and extract their names
                $errorItems[] = $fieldOrDirectiveArgumentValueItem;
            } elseif ($schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessage = $schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessages[$fieldOrDirectiveArgumentValueItem] ?? null) {
                // Check if this enumValue is deprecated
                $deprecationItems[$fieldOrDirectiveArgumentValueItem] = $schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessage;
            }
        }
        if ($errorItems) {
            // Remove the deprecated enumValues from the schema definition
            $fieldOrDirectiveArgumentEnumValues = array_values(array_diff(
                $schemaFieldOrDirectiveArgumentEnumTypeValues,
                array_keys($schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessages)
            ));
            if (count($errorItems) === 1) {
                $errors[] = sprintf(
                    $this->getTranslationAPI()->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($this->getTranslationAPI()->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($this->getTranslationAPI()->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            } else {
                $errors[] = sprintf(
                    $this->getTranslationAPI()->__('Values \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' are not allowed (the only allowed values are: \'%5$s\')', 'component-model'),
                    implode($this->getTranslationAPI()->__('\', \''), $errorItems),
                    $fieldOrDirectiveArgumentName,
                    $type == ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
                    $fieldOrDirectiveName,
                    implode($this->getTranslationAPI()->__('\', \''), $fieldOrDirectiveArgumentEnumValues)
                );
            }
        }
        foreach ($deprecationItems as $fieldOrDirectiveArgumentValueItem => $deprecationItemDescription) {
            $deprecations[] = sprintf(
                $this->getTranslationAPI()->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is deprecated: \'%5$s\'', 'component-model'),
                $fieldOrDirectiveArgumentValueItem,
                $fieldOrDirectiveArgumentName,
                $type == ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
                $fieldOrDirectiveName,
                $deprecationItemDescription
            );
        }
    }
}
