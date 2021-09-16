<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

class MixedScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Mixed';
    }

    /**
     * Accept anything and everything
     */
    public function coerceValue(mixed $inputValue): mixed
    {
        return $inputValue;
    }
}
