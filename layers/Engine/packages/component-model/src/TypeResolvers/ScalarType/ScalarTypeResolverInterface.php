<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

/**
 * Based on GraphQL custom scalars.
 *
 * @see https://www.graphql.de/blog/scalars-in-depth/
 */
interface ScalarTypeResolverInterface extends ConcreteTypeResolverInterface, InputTypeResolverInterface
{
    /**
     * Result coercion. Called by the (GraphQL) engine when printing the response.
     *
     * It takes the scalar entity as an input and it is converted
     * into a format that can be output on the response.
     *
     * `array` is supported as an output type, as to support `JSONObject`.
     *
     * @return string|int|float|bool|array formatted representation of the custom scalar
     */
    public function serialize(mixed $scalarValue): string|int|float|bool|array;

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

    // /**
    //  * Literal input coercion. Called by the (GraphQL) engine to convert an input
    //  * (such as field argument `"Hallo!"` in `{ echo(msg: "Hallo!") }`)
    //  * into the corresponding scalar entity (in this case, a String).
    //  *
    //  * @return mixed the (custom) scalar
    //  * @see https://spec.graphql.org/draft/#sec-Input-Values
    //  */
    // public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed;

    // /**
    //  * Value input coercion.
    //  *
    //  * Similar to `serialize` in that it can take any input: the (custom)
    //  * scalar itself, or a representation of it (as string, int, etc).
    //  *
    //  * Similar to `parseLiteral` in that it must return the scalar entity
    //  *
    //  * @return mixed the (custom) scalar
    //  */
    // public function parseValue(mixed $scalarValue): mixed;
}
