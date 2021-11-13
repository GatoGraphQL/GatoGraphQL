<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;

/**
 * Based on GraphQL custom scalars.
 *
 * @see https://www.graphql.de/blog/scalars-in-depth/
 */
interface ScalarTypeResolverInterface extends ConcreteTypeResolverInterface, InputTypeResolverInterface, LeafOutputTypeResolverInterface
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
}
