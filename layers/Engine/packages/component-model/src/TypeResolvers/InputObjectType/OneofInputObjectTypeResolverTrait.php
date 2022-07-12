<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
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
        $inputValueSize = count((array)$inputValue);
        if ($inputValueSize > 1) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E6,
                        [
                            $this->getMaybeNamespacedTypeName(),
                            $inputValueSize,
                            implode(
                                $this->getTranslationAPI()->__('\', \'', 'component-model'),
                                array_keys((array)$inputValue)
                            ),
                        ]
                    ),
                    $astNode,
                ),
            );
            return;
        }
        if ($inputValueSize === 0 && $this->isOneInputValueMandatory()) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionErrorFeedbackItemProvider::class,
                        InputValueCoercionErrorFeedbackItemProvider::E7,
                        [
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
        }
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
