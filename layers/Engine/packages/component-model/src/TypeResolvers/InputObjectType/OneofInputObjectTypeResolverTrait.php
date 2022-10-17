<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Translation\TranslationAPIInterface;
use stdClass;

trait OneofInputObjectTypeResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;
    abstract public function getMaybeNamespacedTypeName(): string;

    /**
     * The oneof input can be used for different uses, such as:
     *
     *   1. Get a specific user: `{ user(by:{id: 1}) { name } }`
     *   2. Search users: `{ users(searchBy:{name: "leo"}) { id } }`
     *
     * In the first case, the input is mandatory
     * In the second case, it is not
     *
     * Because InputObjects with no value in the query are initialized as {} (via `new stdClass`),
     * then we must explicitly check if the oneof input requires the one value or not.
     */
    protected function isOneInputValueMandatory(): bool
    {
        return true;
    }

    /**
     * Validate that there is exactly one input set
     */
    protected function validateOneofInputObjectValue(
        stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $inputValueAsArray = (array)$inputValue;
        $inputValueSize = count($inputValueAsArray);
        if ($inputValueSize > 1) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_6,
                        [
                            $this->getMaybeNamespacedTypeName(),
                            $inputValueSize,
                            implode(
                                $this->getTranslationAPI()->__('\', \'', 'component-model'),
                                array_keys($inputValueAsArray)
                            ),
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if ($inputValueSize === 0) {
            if ($this->isOneInputValueMandatory()) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                            InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_7,
                            [
                                $this->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        $astNode,
                    ),
                );
            }
            return;
        }
        /** @var string */
        $selectedInputPropertyName = array_keys($inputValueAsArray)[0];
        /** @var mixed */
        $selectedInputPropertyValue = $inputValue->$selectedInputPropertyName;
        if ($selectedInputPropertyValue === null
            && !$this->isOneOfInputPropertyNullable($selectedInputPropertyName)
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_19,
                        [
                            $selectedInputPropertyName,
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
        }
    }

    protected function isOneOfInputPropertyNullable(
        string $propertyName
    ): bool {
        return false;
    }

    /**
     * Do not initialize the OneofInputObject, since we do not know
     * which one option to initialize
     */
    protected function initializeInputFieldInputObjectValue(): bool
    {
        return false;
    }
}
