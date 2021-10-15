<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

class AnyScalarScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyScalar';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Wildcard type to represent any built-in type (string, int, bool, etc)', 'component-model');
    }

    /**
     * Accept anything and everything, other than arrays and objects
     */
    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }
        return $inputValue;
    }
}
