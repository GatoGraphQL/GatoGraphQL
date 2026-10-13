<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\ComponentModel\TypeResolvers\OutputValueCoercingLeafTypeResolverInterface;
use stdClass;

use function is_bool;
use function is_finite;
use function is_float;
use function is_int;
use function is_string;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class StringScalarTypeResolver extends AbstractScalarTypeResolver implements OutputValueCoercingLeafTypeResolverInterface
{
    use BuiltInScalarTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'String';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The String scalar type represents textual data, represented as UTF-8 character sequences.', 'gatographql');
    }

    /**
     * Fields of type `String` must return a string.
     * Numbers and booleans can be coerced.
     *
     * @see https://spec.graphql.org/draft/#sec-String.Result-Coercion
     */
    public function coerceOutputValue(string|int|float|bool|array|stdClass $serializedValue): string|int|float|bool|null
    {
        if (is_bool($serializedValue)) {
            return $serializedValue ? 'true' : 'false';
        }
        if (is_float($serializedValue) && !is_finite($serializedValue)) {
            return null;
        }
        if (is_string($serializedValue) || is_int($serializedValue) || is_float($serializedValue)) {
            return (string) $serializedValue;
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
        return (string) $inputValue;
    }
}
