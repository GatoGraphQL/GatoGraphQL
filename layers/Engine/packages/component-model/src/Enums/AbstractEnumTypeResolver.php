<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    /**
     * Provide a mapping of enum values from output to real value,
     * such as entries [VALUE => value] for "uppercase" output
     *
     * @var array<string,string>|null
     */
    protected ?array $outputValueToValueMappings = null;
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
     * By default, output the enum value in UPPERCASE
     */
    public function getOutputEnumValueCallable(): ?callable
    {
        return 'strtoupper';
    }

    /**
     * The values in the enum as they must be output (eg: in UPPERCASE)
     *
     * @return string[]
     */
    final public function getEnumOutputValues(): array
    {
        $outputValueToValueMappings = $this->getOutputValueToValueMappings();
        return array_keys($outputValueToValueMappings);
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
        $outputValueToValueMappings = $this->getOutputValueToValueMappings();
        return $outputValueToValueMappings[$inputEnumValue] ?? null;
    }

    /**
     * Provide a mapping of enum values from uppercase to real value,
     * as entries: [VALUE => value]
     *
     * @return array<string,string>
     */
    private function getOutputValueToValueMappings(): array
    {
        if ($this->outputValueToValueMappings === null) {
            $this->outputValueToValueMappings = [];
            foreach ($this->getEnumValues() as $enumValue) {
                $this->outputValueToValueMappings[$this->getOutputEnumValue($enumValue)] = $enumValue;
            }
        }
        return $this->outputValueToValueMappings;
    }

    /**
     * Transform the enum value to its output, or return
     * the same enum value if there is no callable provided
     */
    private function getOutputEnumValue(string $enumValue): string
    {
        $outputEnumValueCallable = $this->getOutputEnumValueCallable();
        if ($outputEnumValueCallable !== null) {
            return $outputEnumValueCallable($enumValue);
        }
        return $enumValue;
    }

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
     *
     * @param string $enumValue
     * @return string|null
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
}
