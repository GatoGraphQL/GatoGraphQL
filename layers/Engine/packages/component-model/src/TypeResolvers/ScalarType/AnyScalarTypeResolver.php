<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

class AnyScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyScalar';
    }
    
    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        return $scalarValue;
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        return $inputValue;
    }
}
