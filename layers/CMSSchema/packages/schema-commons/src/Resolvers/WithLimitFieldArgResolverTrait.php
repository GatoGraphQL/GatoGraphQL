<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Resolvers;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\Root\Translation\TranslationAPIInterface;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

trait WithLimitFieldArgResolverTrait
{
    private ?string $limitFilterInputName = null;

    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    /**
     * Check the limit is not above the max limit or below -1
     */
    protected function maybeValidateLimitFieldArgument(
        int $maxLimit,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): ?string {
        // Check we are dealing with the "limit" fieldArg
        if ($this->limitFilterInputName === null) {
            $this->limitFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                CommonFilterInputComponentProcessor::class,
                CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_LIMIT
            ));
        }
        if ($fieldArgName !== $this->limitFilterInputName) {
            return null;
        }

        return $this->validateLimitFieldArgument(
            $maxLimit,
            $fieldName,
            $fieldArgName,
            $fieldArgValue
        );
    }

    /**
     * Check the limit is not above the max limit or below -1
     */
    protected function validateLimitFieldArgument(
        int $maxLimit,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): ?string {
        // Check the value is not below what is accepted
        $minLimit = $maxLimit === -1 ? -1 : 1;
        if ($fieldArgValue < $minLimit) {
            return sprintf(
                $this->getTranslationAPI()->__('The value for argument \'%s\' in field \'%s\' cannot be below \'%s\'', 'schema-commons'),
                $fieldArgName,
                $fieldName,
                $minLimit
            );
        }

        // Check the value is not below the max limit
        if ($maxLimit !== -1 && $fieldArgValue > $maxLimit) {
            return sprintf(
                $this->getTranslationAPI()->__('The value for argument \'%s\' in field \'%s\' cannot be above \'%s\', but \'%s\' was provided', 'schema-commons'),
                $fieldArgName,
                $fieldName,
                $maxLimit,
                $fieldArgValue
            );
        }

        return null;
    }
}
