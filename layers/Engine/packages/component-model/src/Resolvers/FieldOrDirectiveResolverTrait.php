<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
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
        FieldDataAccessorInterface $fieldOrDirectiveDataAccessor,/*|DirectiveDataAccessorInterface*/
        string $type
    ): ?FeedbackItemResolution {
        $argumentKeyValues = $fieldOrDirectiveDataAccessor->getKeyValues();
        $missing = array_values(array_filter(
            $mandatoryFieldOrDirectiveArgNames,
            fn (string $fieldArgName) => ($argumentKeyValues[$fieldArgName] ?? null) === null
        ));
        if ($missing !== []) {
            return count($missing) === 1 ?
                new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E24,
                    [
                        $missing[0],
                        $type,
                        $fieldOrDirectiveDataAccessor->getFieldName()
                    ]
                )
                : new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E25,
                    [
                        implode($this->getTranslationAPI()->__('\', \''), $missing),
                        $type,
                        $fieldOrDirectiveDataAccessor->getFieldName()
                    ]
                );
        }
        return null;
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
