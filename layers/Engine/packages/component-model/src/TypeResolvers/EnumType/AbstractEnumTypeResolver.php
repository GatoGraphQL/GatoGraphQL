<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use stdClass;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /** @var string[]|null */
    protected ?array $consolidatedEnumValuesCache = null;
    /** @var array<string, string|null> */
    protected array $consolidatedEnumValueDescriptionCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedEnumValueDeprecationMessageCache = [];

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

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    final public function getConsolidatedEnumValues(): array
    {
        if ($this->consolidatedEnumValuesCache !== null) {
            return $this->consolidatedEnumValuesCache;
        }

        /**
         * Allow to override/extend the enum values
         */
        $this->consolidatedEnumValuesCache = $this->getHooksAPI()->applyFilters(
            HookNames::ENUM_VALUES,
            $this->getEnumValues(),
            $this,
        );
        return $this->consolidatedEnumValuesCache;
    }

    final public function getConsolidatedEnumValueDescription(string $enumValue): ?string
    {
        // Cache the result
        if (array_key_exists($enumValue, $this->consolidatedEnumValueDescriptionCache)) {
            return $this->consolidatedEnumValueDescriptionCache[$enumValue];
        }
        return $this->consolidatedEnumValueDescriptionCache[$enumValue] = $this->getHooksAPI()->applyFilters(
            HookNames::ENUM_VALUE_DESCRIPTION,
            $this->getEnumValueDescription($enumValue),
            $this,
            $enumValue,
        );
    }

    final public function getConsolidatedEnumValueDeprecationMessage(string $enumValue): ?string
    {
        // Cache the result
        if (array_key_exists($enumValue, $this->consolidatedEnumValueDeprecationMessageCache)) {
            return $this->consolidatedEnumValueDeprecationMessageCache[$enumValue];
        }
        return $this->consolidatedEnumValueDeprecationMessageCache[$enumValue] = $this->getHooksAPI()->applyFilters(
            HookNames::ENUM_VALUE_DEPRECATION_MESSAGE,
            $this->getEnumValueDeprecationMessage($enumValue),
            $this,
            $enumValue,
        );
    }
}
