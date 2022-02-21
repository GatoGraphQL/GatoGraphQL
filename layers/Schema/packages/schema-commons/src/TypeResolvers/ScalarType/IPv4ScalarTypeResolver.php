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
class IPv4ScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'IPv4';
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4001#section-3';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('IPv4 scalar, such as 192.168.0.1', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        \PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $error;
        }
        return $inputValue;
    }
}
