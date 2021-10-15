<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class DomainScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Domain';
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }
        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_DOMAIN)) {
            return $error;
        }
        return $inputValue;
    }
}
