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

    public function getTypeDescription(): ?string
    {
        return $this->__('Domain scalar, such as https://mysite.com or http://www.mysite.org', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        \PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        if ($error = $this->validateFilterVar($inputValue, \FILTER_VALIDATE_DOMAIN)) {
            return $error;
        }
        return $inputValue;
    }
}
