<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class TimeScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Time';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Time scalar, such as /([a-zA-Z_][0-9a-zA-Z_]*)/', 'component-model');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }

        $castInputValue = strtotime((string) $inputValue);
        if ($castInputValue === false) {
            return $this->getError($this->getDefaultErrorMessage($inputValue));
        }
        return $castInputValue;
    }
}
