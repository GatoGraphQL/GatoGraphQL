<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
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
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }

    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        /**
         * Convert stdClass to array, and apply recursively
         * (i.e. if some stdClass property is stdClass or object)
         */
        if ($scalarValue instanceof stdClass) {
            return array_map(
                function (mixed $scalarValueArrayElem): string|int|float|bool|array|null {
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
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        if (!($inputValue instanceof stdClass)) {
            return;
        }
        $schemaInputValidationFeedbackStore->addError(
            new SchemaInputValidationFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E1,
                    [
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
    }

    final protected function validateFilterVar(
        mixed $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
        int $filter,
        array|int $options = [],
    ): void {
        $valid = \filter_var($inputValue, $filter, $options);
        if ($valid !== false) {
            return;
        }
        $schemaInputValidationFeedbackStore->addError(
            new SchemaInputValidationFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E2,
                    [
                        $inputValue,
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
    }

    final protected function validateIsString(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        if (is_string($inputValue)) {
            return;
        }
        $schemaInputValidationFeedbackStore->addError(
            new SchemaInputValidationFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E3,
                    [
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
    }

    protected function addDefaultError(
        mixed $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        $schemaInputValidationFeedbackStore->addError(
            new SchemaInputValidationFeedback(
                new FeedbackItemResolution(
                    InputValueCoercionErrorFeedbackItemProvider::class,
                    InputValueCoercionErrorFeedbackItemProvider::E16,
                    [
                        $inputValue,
                        $this->getMaybeNamespacedTypeName(),
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this
            ),
        );
    }
}
