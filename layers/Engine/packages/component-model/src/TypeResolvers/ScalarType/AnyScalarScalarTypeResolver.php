<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use stdClass;

class AnyScalarScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyScalar';
    }

    /**
     * Accept anything and everything
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass
    {
        return $inputValue;
    }
}
