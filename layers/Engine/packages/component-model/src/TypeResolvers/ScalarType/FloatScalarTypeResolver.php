<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use CastToType;
use stdClass;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class FloatScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Float';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The Float scalar type represents float numbers.', 'component-model');
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        \PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }

        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue === null) {
            return $this->getError($this->getDefaultErrorMessage($inputValue));
        }
        return (float) $castInputValue;
    }
}
