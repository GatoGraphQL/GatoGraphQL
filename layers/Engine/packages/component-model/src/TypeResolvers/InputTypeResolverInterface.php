<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

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
     * (such as field argument `"Hallo!"` in `{ echo(msg: "Hallo!") }`)
     * into the corresponding scalar entity (in this case, a String).
     *
     * Return an instance of Error if the coercing cannot be done,
     * with a descriptive error message.
     *
     * @param mixed $inputValue the (custom) scalar in any format: itself (eg: an object) or its representation (eg: as a string)
     * @return mixed the coerced (custom) scalar, or an instance of Error if it can't be done
     *
     * @see https://spec.graphql.org/draft/#sec-Input-Values
     */
    public function coerceValue(mixed $inputValue): mixed;
}
