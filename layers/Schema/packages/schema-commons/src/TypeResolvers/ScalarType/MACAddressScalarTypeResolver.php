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
class MACAddressScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'MACAddress';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('MAC (media access control) address scalar, such as 00:1A:C2:7B:00:47', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_MAC)) {
            return $error;
        }
        return $inputValue;
    }
}
