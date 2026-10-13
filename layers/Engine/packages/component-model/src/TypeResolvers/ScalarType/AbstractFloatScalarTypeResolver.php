<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\TypeResolvers\OutputValueCoercingLeafTypeResolverInterface;
use stdClass;

use function is_bool;
use function is_finite;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
abstract class AbstractFloatScalarTypeResolver extends AbstractScalarTypeResolver implements OutputValueCoercingLeafTypeResolverInterface
{
    /**
     * Fields of type `Float` must return a finite
     * double-precision floating-point value. Integers,
     * numeric strings and booleans can be coerced.
     *
     * @see https://spec.graphql.org/draft/#sec-Float.Result-Coercion
     */
    public function coerceOutputValue(string|int|float|bool|array|stdClass $serializedValue): string|int|float|bool|null
    {
        if (is_bool($serializedValue) || is_int($serializedValue)) {
            return (float) $serializedValue;
        }
        if (is_string($serializedValue)) {
            if (!is_numeric($serializedValue)) {
                return null;
            }
            $serializedValue = (float) $serializedValue;
        }
        if (!is_float($serializedValue) || !is_finite($serializedValue)) {
            return null;
        }
        return $serializedValue;
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string|int|float|bool $inputValue */

        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (float) $castInputValue;
    }
}
