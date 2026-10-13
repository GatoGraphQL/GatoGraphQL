<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use stdClass;

/**
 * Scalar and Enum types that coerce their values in the response,
 * as defined in the GraphQL spec (eg: an `Int` must be a 32-bit
 * integer). Applied only when the spec-compliant error handling
 * is enabled.
 *
 * @see https://spec.graphql.org/draft/#CoerceResult()
 */
interface OutputValueCoercingLeafTypeResolverInterface extends LeafOutputTypeResolverInterface
{
    /**
     * Coerce the serialized value of a field in the response.
     *
     * @param string|int|float|bool|mixed[]|stdClass $serializedValue The value, as produced by `serialize`
     * @return string|int|float|bool|null The coerced value, or `null` if it cannot be coerced (which raises an execution error)
     */
    public function coerceOutputValue(string|int|float|bool|array|stdClass $serializedValue): string|int|float|bool|null;
}
