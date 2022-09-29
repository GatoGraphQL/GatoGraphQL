<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements ScalarTypeResolverInterface
{
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final public function setObjectSerializationManager(ObjectSerializationManagerInterface $objectSerializationManager): void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        /** @var ObjectSerializationManagerInterface */
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }

    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    /**
     * @return string|int|float|bool|mixed[]|stdClass
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array|stdClass
    {
        /**
         * Convert stdClass to array, and apply recursively
         * (i.e. if some stdClass property is stdClass or object)
         */
        if ($scalarValue instanceof stdClass) {
            return (object)array_map(
                function (mixed $scalarValueArrayElem): string|int|float|bool|array|null|stdClass {
                    if ($scalarValueArrayElem === null) {
                        return null;
                    }
                    if (is_array($scalarValueArrayElem)) {
                        // Convert from array to stdClass
                        $scalarValueArrayElem = (object) $scalarValueArrayElem;
                    }
                    return $this->serialize($scalarValueArrayElem);
                },
                (array) $scalarValue
            );
        }
        // Convert object to string
        if (is_object($scalarValue)) {
            return $this->getObjectSerializationManager()->serialize($scalarValue);
        }
        // Return as is
        return $scalarValue;
    }

    final protected function validateIsNotStdClass(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!($inputValue instanceof stdClass)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_1,
                    [
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                $astNode,
            ),
        );
    }

    /**
     * @param array<string,mixed>|int $options
     */
    final protected function validateFilterVar(
        mixed $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        int $filter,
        array|int $options = [],
    ): void {
        $valid = \filter_var($inputValue, $filter, $options);
        if ($valid !== false) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_2,
                    [
                        $inputValue,
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                $astNode,
            ),
        );
    }

    final protected function validateIsString(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (is_string($inputValue)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_3,
                    [
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                $astNode,
            ),
        );
    }

    protected function addDefaultError(
        mixed $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                    InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_16,
                    [
                        $inputValue,
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                $astNode,
            ),
        );
    }
}
