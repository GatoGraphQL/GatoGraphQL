<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

class AnyScalarScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyScalar';
    }
    
    /**
     * Accept anything and everything
     */
    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        return $scalarValue;
    }

    /**
     * Accept anything and everything
     */
    public function coerceValue(mixed $inputValue): mixed
    {
        return $inputValue;
    }
}
