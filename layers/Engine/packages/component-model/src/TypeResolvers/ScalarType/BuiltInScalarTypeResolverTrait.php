<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;

/**
 * Built-in scalars must not be namespaced
 */
trait BuiltInScalarTypeResolverTrait
{
    use CanonicalTypeNameTypeResolverTrait;
}
