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
class IPv6ScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IPv6';
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4291#section-2.2';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('IPv6 scalar, such as 2001:0db8:85a3:08d3:1319:8a2e:0370:7334', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $error;
        }
        return $inputValue;
    }
}
