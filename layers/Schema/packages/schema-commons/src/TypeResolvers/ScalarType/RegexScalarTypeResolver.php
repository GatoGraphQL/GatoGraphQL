<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class RegexScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Regex';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }
        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_REGEXP)) {
            return $error;
        }
        return $inputValue;
    }
}
