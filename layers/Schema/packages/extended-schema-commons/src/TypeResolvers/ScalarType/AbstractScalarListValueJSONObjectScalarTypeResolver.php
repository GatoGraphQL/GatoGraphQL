<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

abstract class AbstractScalarListValueJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
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

        $inputValueArray = (array) $inputValue;
        foreach ($inputValueArray as $key => $value) {
            /**
             * Check the value is an array
             */
            if (!is_array($value)) {
                $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                return null;
            }

            /**
             * Check the array elements are scalars
             */
            foreach ($value as $valueKey => $valueElem) {
                /**
                 * If the value is of any of the following types, it can't be
                 * coerced to string:
                 *
                 * - null
                 * - array
                 * - object
                 */
                if ($valueElem === null || is_array($valueElem) || is_object($valueElem)) {
                    $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                    return null;
                }
                /**
                 * Coerce to the specific scalar type, if possible
                 */
                if (!$this->canCastJSONObjectPropertyValue($valueElem)) {
                    $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
                    return null;
                }
                $value[$valueKey] = $this->castJSONObjectPropertyValue($valueElem);
            }
            $inputValue->$key = $value;
        }
        return $inputValue;
    }

    abstract protected function canCastJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): bool;

    abstract protected function castJSONObjectPropertyValue(
        string|int|float|bool $value,
    ): string|int|float|bool;
}
