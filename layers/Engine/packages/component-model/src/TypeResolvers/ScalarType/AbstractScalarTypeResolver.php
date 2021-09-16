<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements ScalarTypeResolverInterface
{
    /**
     * By default, the value is serialized as is
     */
    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        return $scalarValue;
    }
}
