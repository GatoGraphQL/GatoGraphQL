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
     * As specified by the GraphQL spec on directive @specifiedBy:
     *
     *   The @specifiedBy built-in directive is used within
     *   the type system definition language to provide a
     *   scalar specification URL for specifying the behavior
     *   of custom scalar types.
     *
     *   The URL should point to a human-readable specification
     *   of the data format, serialization, and coercion rules.
     *   It must not appear on built-in scalar types.
     *
     * @see https://spec.graphql.org/draft/#sec--specifiedBy
     */
    public function getSpecifiedByURL(): ?string;

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
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array;

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
