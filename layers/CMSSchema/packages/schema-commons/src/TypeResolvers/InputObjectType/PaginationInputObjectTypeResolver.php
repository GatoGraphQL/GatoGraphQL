<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class PaginationInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
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

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'limit' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_LIMIT],
            'offset' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_OFFSET],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
