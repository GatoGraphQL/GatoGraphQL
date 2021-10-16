<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use stdClass;

class AnyBuiltInScalarScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyBuiltInScalar';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Wildcard type representing any of GraphQL\'s built-in types (String, Int, Boolean, Float or ID)', 'component-model');
    }

    /**
     * Accept anything and everything, other than arrays and objects
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }
        return $inputValue;
    }
}
