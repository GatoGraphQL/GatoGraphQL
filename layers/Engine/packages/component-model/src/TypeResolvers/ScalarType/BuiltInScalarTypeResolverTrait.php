<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

trait BuiltInScalarTypeResolverTrait
{
    /**
     * Built-in scalars must not be namespaced
     */
    public function getNamespace(): string
    {
        return '';
    }
}
