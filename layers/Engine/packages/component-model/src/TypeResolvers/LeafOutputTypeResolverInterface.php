<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use stdClass;

/**
 * This definition of "LeafOutput" type is different than the "Output"
 * type defined in the GraphQL spec:
 *
 * @see https://spec.graphql.org/draft/#sec-Input-and-Output-Types
 *
 * In the GraphQL spec, the OutputType also includes ObjectType and InterfaceType.
 * In this case, "LeafOutput" types do not include them. It includes
 * only the types which can reach a leaf, and will be printed on the response:
 *
 * - ScalarType
 * - EnumType
 *
 * Only these types need be called ->serialize() on them
 */
interface LeafOutputTypeResolverInterface extends OutputTypeResolverInterface
{
    /**
     * Result coercion. Called by the (GraphQL) engine when printing the response.
     *
     * It takes the scalar entity as an input and it is converted
     * into a format that can be output on the response.
     *
     * `array` is supported as an output type, as to support `JSONObject`.
     *
     * @return string|int|float|bool|mixed[]|stdClass formatted representation of the custom scalar
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array|stdClass;
}
