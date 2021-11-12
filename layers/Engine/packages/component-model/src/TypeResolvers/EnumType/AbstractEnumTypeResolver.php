<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use stdClass;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /**
     * By default, no description
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return null;
    }

    /**
     * Deprecation message for a specific enum value
     */
    public function getEnumValueDeprecationMessage(string $enumValue): ?string
    {
        return null;
    }

    /**
     * The validation that the enum value is valid is done in
     * `doValidateEnumFieldOrDirectiveArgumentsItem`.
     *
     * This function simply returns the same value always.
     */
    final public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        return $inputValue;
    }
}
