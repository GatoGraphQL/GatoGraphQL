<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

class NumericScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Numeric';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Wildcard type representing any of the numeric types (Int or Float)', 'engine');
    }

    /**
     * Cast to either Int or Float
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }
        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue !== null) {
            return (int) $castInputValue;
        }
        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue !== null) {
            return (float) $castInputValue;
        }
        return $this->getError($this->getDefaultErrorMessage($inputValue));
    }
}
