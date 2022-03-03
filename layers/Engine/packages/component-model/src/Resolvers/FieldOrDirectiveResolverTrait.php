<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\Root\Translation\TranslationAPIInterface;

trait FieldOrDirectiveResolverTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;

    abstract protected function getTranslationAPI(): TranslationAPIInterface;

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
    ): ?FeedbackItemResolution {
        $missing = array_values(array_filter(
            $mandatoryFieldOrDirectiveArgNames,
            fn (string $fieldArgName) => !isset($fieldOrDirectiveArgs[$fieldArgName])
        ));
        if ($missing !== []) {
            return count($missing) === 1 ?
                new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E24,
                    [
                        $missing[0],
                    ]
                )
                : new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E25,
                    [
                        implode($this->getTranslationAPI()->__('\', \''), $missing),
                    ]
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
     * Same with expressions, as when calling `getSelfProp(%{self}%, "posts")`.
     *
     * But no need with variables, because by now they will have been replaced with the actual value.
     */
    protected function canValidateFieldOrDirectiveArgumentsWithValuesForSchema(array $fieldOrDirectiveArgs): bool
    {
        return !FieldQueryUtils::isAnyFieldArgumentValueAFieldOrExpression($fieldOrDirectiveArgs);
    }

    /**
     * @param string[] $deprecations
     * @param string[] $fieldOrDirectiveArgumentValueItems
     */
    private function doValidateEnumFieldOrDirectiveArgumentDeprecationsItem(
        array &$deprecations,
        EnumTypeResolverInterface $fieldOrDirectiveArgumentEnumTypeResolver,
        array $fieldOrDirectiveArgumentValueItems,
        string $fieldOrDirectiveArgumentName,
        string $fieldOrDirectiveName,
        string $type
    ): void {
        foreach ($fieldOrDirectiveArgumentValueItems as $fieldOrDirectiveArgumentValueItem) {
            $schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessage = $fieldOrDirectiveArgumentEnumTypeResolver->getConsolidatedEnumValueDeprecationMessage($fieldOrDirectiveArgumentValueItem);
            if (empty($schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessage)) {
                continue;
            }
            // This enumValue is deprecated
            $deprecations[] = sprintf(
                $this->getTranslationAPI()->__('Value \'%1$s\' for argument \'%2$s\' in %3$s \'%4$s\' is deprecated: \'%5$s\'', 'component-model'),
                $fieldOrDirectiveArgumentValueItem,
                $fieldOrDirectiveArgumentName,
                $type === ResolverTypes::FIELD ? $this->getTranslationAPI()->__('field', 'component-model') : $this->getTranslationAPI()->__('directive', 'component-model'),
                $fieldOrDirectiveName,
                $schemaFieldOrDirectiveArgumentEnumTypeDeprecationMessage
            );
        }
    }
}
