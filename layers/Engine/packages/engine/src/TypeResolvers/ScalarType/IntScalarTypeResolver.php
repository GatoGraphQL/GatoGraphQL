<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use CastToType;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class IntScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Int';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Int scalar type represents non-fractional signed whole numeric values.', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }

        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue === null) {
            return $this->getError($this->getDefaultErrorMessage($inputValue));
        }
        return (int) $castInputValue;
    }
}
