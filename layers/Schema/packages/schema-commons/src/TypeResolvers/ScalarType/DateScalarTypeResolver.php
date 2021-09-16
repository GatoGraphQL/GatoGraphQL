<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Custom Scalar
 * 
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class DateScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Date';
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
