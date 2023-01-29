<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

/**
 * Input types are those that can be provided inputs via field arguments:
 *
 * - ScalarType
 * - EnumType
 * - InputObjectType
 */
interface InputTypeResolverInterface extends TypeResolverInterface
{
    /**
     * It handles both "Literal input coercion" and "Value input coercion"
     * from the GraphQL spec.
     *
     * Called by the (GraphQL) engine to convert an input
     * (such as field argument `"Hallo!"` in `{ _echo(msg: "Hallo!") }`)
     * into the corresponding scalar entity (in this case, a String).
     *
     * Return `null` if the coercing cannot be done, and add an error
     * with a descriptive message to `$objectTypeFieldResolutionFeedbackStore`.
     *
     * @param string|int|float|bool|stdClass $inputValue the (custom) scalar in any format: itself (eg: an object) or its representation (eg: as a string)
     * @return string|int|float|bool|object|null the coerced (custom) scalar, or `null` if it can't be done
     *
     * @see https://spec.graphql.org/draft/#sec-Input-Values
     */
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null;
}
