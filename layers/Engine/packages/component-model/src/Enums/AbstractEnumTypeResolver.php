<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractEnumTypeResolver extends AbstractTypeResolver implements EnumTypeResolverInterface
{
    protected ?array $uppercaseValueMappings = null;

    public function outputEnumValueInUppercase(): bool
    {
        return true;
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
        if (in_array($inputEnumValue, $this->getValues())) {
            return $inputEnumValue;
        }
        return null;
    }

    protected function getUppercaseValueMappings(): array
    {
        if ($this->uppercaseValueMappings === null) {
            $this->uppercaseValueMappings = array_map(
                'strtoupper',
                $this->getValues()
            );
        }
        return $this->uppercaseValueMappings;
    }

    public function getDescriptions(): array
    {
        return [];
    }
}
