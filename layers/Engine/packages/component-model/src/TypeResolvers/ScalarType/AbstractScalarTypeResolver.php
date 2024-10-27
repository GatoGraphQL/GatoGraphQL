<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements ScalarTypeResolverInterface
{
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        if ($this->objectSerializationManager === null) {
            /** @var ObjectSerializationManagerInterface */
            $objectSerializationManager = $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
            $this->objectSerializationManager = $objectSerializationManager;
        }
        return $this->objectSerializationManager;
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
         * Convert object to string or stdClass and,
         * in the latter case, it will be serialized yet again
         */
        if (is_object($scalarValue) && !($scalarValue instanceof stdClass)) {
            $scalarValue = $this->getObjectSerializationManager()->serialize($scalarValue);
        }

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
                        // Convert from array to stdClass and back
                        return (array)$this->serialize((object)$scalarValueArrayElem);
                    }
                    return $this->serialize($scalarValueArrayElem);
                },
                (array) $scalarValue
            );
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

    /**
     * @param array<string,mixed> $extensions
     */
    protected function addDefaultError(
        mixed $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $extensions = [],
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
                $extensions,
            ),
        );
    }
}
