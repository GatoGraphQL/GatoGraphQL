<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoPSchema\ExtendedSchemaCommons\FeedbackItemProviders\InputValueCoercionErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

abstract class AbstractListValueJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Custom scalar representing a JSON Object where values are lists (of anything)%s', 'extended-schema-commons'),
            $this->canValueBeNullable() ? $this->__(' or null', 'extended-schema-commons') : '',
        );
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $inputValue = parent::coerceValue(
            $inputValue,
            $astNode,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($inputValue === null) {
            return $inputValue;
        }
        /** @var stdClass $inputValue */

        $canValueBeNullable = $this->canValueBeNullable();
        $inputValueArray = (array) $inputValue;
        foreach ($inputValueArray as $key => $value) {
            /**
             * Check the value is an array, or null (if allowed)
             */
            if (!(is_array($value) || ($canValueBeNullable && $value === null))) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            InputValueCoercionErrorFeedbackItemProvider::class,
                            InputValueCoercionErrorFeedbackItemProvider::E3,
                            [
                                $this->getMaybeNamespacedTypeName(),
                                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($inputValue),
                            ]
                        ),
                        $astNode,
                    ),
                );
                return null;
            }
            $inputValue->$key = $value;
        }
        return $inputValue;
    }

    abstract protected function canValueBeNullable(): bool;
}
