<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoPCMSSchema\SchemaCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\SchemaCommons\FilterInputs\LimitFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\OffsetFilterInput;

class PaginationInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?OffsetFilterInput $excludeIDsFilterInput = null;
    private ?LimitFilterInput $includeFilterInput = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getOffsetFilterInput(): OffsetFilterInput
    {
        if ($this->excludeIDsFilterInput === null) {
            /** @var OffsetFilterInput */
            $excludeIDsFilterInput = $this->instanceManager->getInstance(OffsetFilterInput::class);
            $this->excludeIDsFilterInput = $excludeIDsFilterInput;
        }
        return $this->excludeIDsFilterInput;
    }
    final protected function getLimitFilterInput(): LimitFilterInput
    {
        if ($this->includeFilterInput === null) {
            /** @var LimitFilterInput */
            $includeFilterInput = $this->instanceManager->getInstance(LimitFilterInput::class);
            $this->includeFilterInput = $includeFilterInput;
        }
        return $this->includeFilterInput;
    }

    public function getTypeName(): string
    {
        return 'PaginationInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
     */
    protected function validateInputFieldValue(
        string $inputFieldName,
        mixed $inputFieldValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validateInputFieldValue($inputFieldName, $inputFieldValue, $astNode, $objectTypeFieldResolutionFeedbackStore);

        if ($inputFieldName === 'limit' && $this->getMaxLimit() !== null) {
            $this->validateLimitInputField(
                $this->getMaxLimit(),
                $inputFieldName,
                $inputFieldValue,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore
            );
        }
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
        mixed $inputFieldValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Check the value is not below what is accepted
        $minLimit = $maxLimit === -1 ? -1 : 1;
        if ($inputFieldValue < $minLimit) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E1,
                        [
                            $inputFieldName,
                            $this->getMaybeNamespacedTypeName(),
                            $minLimit,
                        ]
                    ),
                    $astNode,
                )
            );
            return;
        }

        // Check the value is not below the max limit
        if ($maxLimit !== -1 && $inputFieldValue > $maxLimit) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E2,
                        [
                            $inputFieldName,
                            $this->getMaybeNamespacedTypeName(),
                            $maxLimit,
                            $inputFieldValue,
                        ]
                    ),
                    $astNode,
                )
            );
            return;
        }
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'limit' => $this->getLimitFilterInput(),
            'offset' => $this->getOffsetFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
