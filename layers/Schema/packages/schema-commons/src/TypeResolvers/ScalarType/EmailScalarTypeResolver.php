<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class EmailScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Email';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Email scalar, such as leo@mysite.com', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc5322#section-3.4.1';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $this->validateFilterVar($inputValue, $schemaInputValidationFeedbackStore, \FILTER_VALIDATE_EMAIL);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $inputValue;
    }
}
