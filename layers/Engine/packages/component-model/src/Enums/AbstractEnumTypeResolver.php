<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /**
     * Provide a mapping of enum values from uppercase to real value,
     * as entries: [VALUE => value]
     *
     * @var array<string,string>|null
     */
    protected ?array $uppercaseValueMappings = null;
    /**
     * Description for all enum values (which have a description)
     * 
     * @var array<string,string>|null
     */
    protected ?array $enumValueDescriptions = null;

    /**
     * By default, output the enum value in UPPERCASE
     */
    public function outputEnumValueInUppercase(): bool
    {
        return true;
    }
    
    /**
     * The values in the enum as they must be output (eg: in UPPERCASE)
     * 
     * @return string[]
     */
    final public function getEnumOutputValues(): array
    {
        if ($this->outputEnumValueInUppercase()) {
            $uppercaseValueMappings = $this->getUppercaseValueMappings();
            return array_keys($uppercaseValueMappings);
        }
        return $this->getEnumValues();
    }

    /**
     * The input may in in UPPERCASE while the enum value in the app
     * is stored in lowercase, then convert from one to the other.
     *
     * @param string $inputEnumValue The input enum value, possibly as UPPERCASE
     * @return string|null The found enum value, or `null` if it doesn't exist
     */
    final public function getEnumValueFromInput(string $inputEnumValue): ?string
    {
        if ($this->outputEnumValueInUppercase()) {
            $uppercaseValueMappings = $this->getUppercaseValueMappings();
            return $uppercaseValueMappings[$inputEnumValue] ?? null;
        }
        if (in_array($inputEnumValue, $this->getEnumValues())) {
            return $inputEnumValue;
        }
        return null;
    }

    /**
     * Provide a mapping of enum values from uppercase to real value,
     * as entries: [VALUE => value]
     *
     * @return array<string,string>
     */
    protected function getUppercaseValueMappings(): array
    {
        if ($this->uppercaseValueMappings === null) {
            $this->uppercaseValueMappings = [];
            foreach ($this->getEnumValues() as $value) {
                $this->uppercaseValueMappings[strtoupper($value)] = $value;
            }
        }
        return $this->uppercaseValueMappings;
    }

    /**
     * Description for all enum values (which have a description)
     * 
     * @return array<string,string> Key: enum, Value: description
     */
    final public function getEnumValueDescriptions(): array
    {
        if ($this->enumValueDescriptions === null) {
            $this->enumValueDescriptions = $this->doGetEnumValueDescriptions();
        }
        return $this->enumValueDescriptions;
    }

    /**
     * @return array<string,string> Key: enum, Value: description
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
     *
     * @param string $enumValue
     * @return string|null
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return null;
    }
}
