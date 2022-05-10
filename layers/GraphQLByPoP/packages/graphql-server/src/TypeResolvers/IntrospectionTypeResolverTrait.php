<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

/**
 * Introspection types need not be namespaced
 */
trait IntrospectionTypeResolverTrait
{
    use CanonicalTypeNameTypeResolverTrait;
}
