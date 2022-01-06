<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

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

    public function getTypeDescription(): ?string
    {
        return $this->__('The String scalar type represents textual data, represented as UTF-8 character sequences.', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }
        return (string) $inputValue;
    }
}
