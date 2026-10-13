<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\TypeResolvers\OutputValueCoercingLeafTypeResolverInterface;
use stdClass;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function strtolower;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class BooleanScalarTypeResolver extends AbstractScalarTypeResolver implements OutputValueCoercingLeafTypeResolverInterface
{
    use BuiltInScalarTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'Boolean';
    }

    /**
     * Fields of type `Boolean` must return a boolean.
     * Numbers (non-zero is `true`) and the strings
     * `"true"`, `"false"`, `"1"`, `"0"` and `""` can be coerced.
     *
     * @see https://spec.graphql.org/draft/#sec-Boolean.Result-Coercion
     */
    public function coerceOutputValue(string|int|float|bool|array|stdClass $serializedValue): string|int|float|bool|null
    {
        if (is_bool($serializedValue)) {
            return $serializedValue;
        }
        if (is_int($serializedValue) || is_float($serializedValue)) {
            return $serializedValue != 0;
        }
        if (is_string($serializedValue)) {
            return match (strtolower($serializedValue)) {
                'true', '1' => true,
                'false', '0', '' => false,
                default => null,
            };
        }
        return null;
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

        /**
         * Watch out! In Library CastToType, an empty string is not false, but it's NULL
         * But for us it must be false, since calling query ?query=and([true,false]) gets transformed to the $field string "[1,]"
         */
        if ($inputValue === '') {
            return false;
        }

        $castInputValue = CastToType::_bool($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (bool) $castInputValue;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Boolean scalar type represents `true` or `false`.', 'gatographql');
    }
}
