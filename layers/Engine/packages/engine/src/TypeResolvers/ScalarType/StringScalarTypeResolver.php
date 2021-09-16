<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class StringScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'String';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }
        return (string) $inputValue;
    }
}
