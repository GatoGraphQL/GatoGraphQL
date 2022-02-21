<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use CastToType;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class IntScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Int';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Int scalar type represents non-fractional signed whole numeric values.', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        $this->validateIsNotStdClass($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        $castInputValue = CastToType::_int($inputValue);
        if ($castInputValue === null) {
            return $this->getError($this->getDefaultErrorMessage($inputValue));
        }
        return (int) $castInputValue;
    }
}
