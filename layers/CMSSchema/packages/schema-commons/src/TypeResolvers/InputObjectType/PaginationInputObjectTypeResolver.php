<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\SchemaCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\LimitFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\OffsetFilterInputProcessor;

class PaginationInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?OffsetFilterInputProcessor $excludeIDsFilterInputProcessor = null;
    private ?LimitFilterInputProcessor $includeFilterInputProcessor = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setOffsetFilterInputProcessor(OffsetFilterInputProcessor $excludeIDsFilterInputProcessor): void
    {
        $this->excludeIDsFilterInputProcessor = $excludeIDsFilterInputProcessor;
    }
    final protected function getOffsetFilterInputProcessor(): OffsetFilterInputProcessor
    {
        return $this->excludeIDsFilterInputProcessor ??= $this->instanceManager->getInstance(OffsetFilterInputProcessor::class);
    }
    final public function setLimitFilterInputProcessor(LimitFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getLimitFilterInputProcessor(): LimitFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(LimitFilterInputProcessor::class);
    }

    public function getTypeName(): string
    {
        return 'PaginationInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'limit' => $this->getIntScalarTypeResolver(),
            'offset' => $this->getIntScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        $maxLimit = $this->getMaxLimit();
        $limitDesc = match ($maxLimit) {
            null => $this->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'schema-commons'),
            -1 => $this->__('Limit the results. \'-1\' brings all the results', 'schema-commons'),
            default => sprintf(
                $this->__('Limit the results. The maximum amount allowed is \'%s\'', 'schema-commons'),
                $maxLimit
            ),
        };
        return match ($inputFieldName) {
            'limit' => $limitDesc,
            'offset' => $this->__('Offset the results by how many positions', 'schema-commons'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'limit' => $this->getDefaultLimit(),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    protected function getDefaultLimit(): ?int
    {
        return null;
    }

    /**
     * Validate constraints on the input field's value
     *
     * @return FeedbackItemResolution[] Errors
     */
    protected function validateInputFieldValue(string $inputFieldName, mixed $inputFieldValue): array
    {
        $errors = parent::validateInputFieldValue($inputFieldName, $inputFieldValue);

        if ($inputFieldName === 'limit' && $this->getMaxLimit() !== null) {
            if (
                $maybeErrorFeedbackItemResolution = $this->validateLimitInputField(
                    $this->getMaxLimit(),
                    $inputFieldName,
                    $inputFieldValue
                )
            ) {
                $errors[] = $maybeErrorFeedbackItemResolution;
            }
        }
        return $errors;
    }

    protected function getMaxLimit(): ?int
    {
        return null;
    }

    /**
     * Check the limit is not above the max limit or below -1
     */
    protected function validateLimitInputField(
        int $maxLimit,
        string $inputFieldName,
        mixed $inputFieldValue
    ): ?FeedbackItemResolution {
        // Check the value is not below what is accepted
        $minLimit = $maxLimit === -1 ? -1 : 1;
        if ($inputFieldValue < $minLimit) {
            return new FeedbackItemResolution(
                FeedbackItemProvider::class,
                FeedbackItemProvider::E1,
                [
                    $inputFieldName,
                    $this->getMaybeNamespacedTypeName(),
                    $minLimit,
                ]
            );
        }

        // Check the value is not below the max limit
        if ($maxLimit !== -1 && $inputFieldValue > $maxLimit) {
            return new FeedbackItemResolution(
                FeedbackItemProvider::class,
                FeedbackItemProvider::E2,
                [
                    $inputFieldName,
                    $this->getMaybeNamespacedTypeName(),
                    $maxLimit,
                    $inputFieldValue,
                ]
            );
        }

        return null;
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'limit' => $this->getLimitFilterInputProcessor(),
            'offset' => $this->getOffsetFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
