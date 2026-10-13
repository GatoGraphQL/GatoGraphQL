<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\TypeResolvers\OutputValueCoercingLeafTypeResolverInterface;
use stdClass;

use function floor;
use function is_bool;
use function is_finite;
use function is_float;
use function is_int;
use function is_string;
use function preg_match;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
abstract class AbstractIntScalarTypeResolver extends AbstractScalarTypeResolver implements OutputValueCoercingLeafTypeResolverInterface
{
    /**
     * Fields of type `Int` must return a 32-bit integer.
     * Integral floats (eg: `1.0`), numeric strings (eg: `"123"`)
     * and booleans can be coerced without losing information.
     *
     * @see https://spec.graphql.org/draft/#sec-Int.Result-Coercion
     */
    public function coerceOutputValue(string|int|float|bool|array|stdClass $serializedValue): string|int|float|bool|null
    {
        if (is_bool($serializedValue)) {
            return (int) $serializedValue;
        }
        if (is_string($serializedValue)) {
            if (preg_match('/^-?\d+$/', $serializedValue) !== 1) {
                return null;
            }
            $serializedValue = (float) $serializedValue;
        }
        if (is_float($serializedValue)) {
            if (!is_finite($serializedValue) || floor($serializedValue) !== $serializedValue) {
                return null;
            }
        }
        if (!is_int($serializedValue) && !is_float($serializedValue)) {
            return null;
        }
        if ($serializedValue < -2147483648 || $serializedValue > 2147483647) {
            return null;
        }
        return (int) $serializedValue;
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

        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (int) $castInputValue;
    }
}
