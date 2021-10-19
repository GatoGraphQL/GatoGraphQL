<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use stdClass;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /**
     * Description for all enum values (which have a description)
     *
     * @var array<string,string>|null
     */
    protected ?array $enumValueDescriptions = null;
    /**
     * Deprecation message for all enum values (which are deprecated)
     *
     * @var array<string,string> Key: enum value, Value: deprecation message
     */
    protected ?array $enumValueDeprecationMessages = null;

    /**
     * Description for all enum values (which have a description)
     *
     * @return array<string,string> Key: enum value, Value: description
     */
    final public function getEnumValueDescriptions(): array
    {
        if ($this->enumValueDescriptions === null) {
            $this->enumValueDescriptions = $this->doGetEnumValueDescriptions();
        }
        return $this->enumValueDescriptions;
    }

    /**
     * @return array<string,string> Key: enum value, Value: description
     */
    private function doGetEnumValueDescriptions(): array
    {
        $enumValueDescriptions = [];
        foreach ($this->getEnumValues() as $enumValue) {
            $enumValueDescription = $this->getEnumValueDescription($enumValue);
            if ($enumValueDescription !== null) {
                $enumValueDescriptions[$enumValue] = $enumValueDescription;
            }
        }
        return $enumValueDescriptions;
    }

    /**
     * By default, no description
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return null;
    }

    /**
     * Deprecation message for all enum values (which are deprecated)
     *
     * @return array<string,string> Key: enum value, Value: deprecation message
     */
    final public function getEnumValueDeprecationMessages(): array
    {
        if ($this->enumValueDeprecationMessages === null) {
            $this->enumValueDeprecationMessages = $this->doGetEnumValueDeprecationMessages();
        }
        return $this->enumValueDeprecationMessages;
    }

    /**
     * @return array<string,string> Key: enum value, Value: deprecation message
     */
    private function doGetEnumValueDeprecationMessages(): array
    {
        $enumValueDeprecationMessages = [];
        foreach ($this->getEnumValues() as $enumValue) {
            $enumValueDeprecationMessage = $this->getEnumValueDeprecationMessage($enumValue);
            if ($enumValueDeprecationMessage !== null) {
                $enumValueDeprecationMessages[$enumValue] = $enumValueDeprecationMessage;
            }
        }
        return $enumValueDeprecationMessages;
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
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        return $inputValue;
    }
}
