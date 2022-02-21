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
class RegexScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Regex';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Regex scalar, such as /([a-zA-Z_][0-9a-zA-Z_]*)/', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $this->validateFilterVar($inputValue, $schemaInputValidationFeedbackStore, \FILTER_VALIDATE_REGEXP);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $inputValue;
    }
}
