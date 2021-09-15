<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface EnumTypeResolverInterface extends TypeResolverInterface
{
    /**
     * The values in the enum
     * 
     * @return string[]
     */
    public function getEnumValues(): array;
    /**
     * @return array<string,string> Key: enum, Value: description
     */
    public function getEnumDescriptions(): array;
    /**
     * Enable to output the enum values in UPPERCASE,
     * even if those values are handled as lowercase
     */
    public function outputEnumValueInUppercase(): bool;
    /**
     * The input may in in UPPERCASE while the enum value in the app
     * is stored in lowercase, then convert from one to the other.
     *
     * @param string $inputEnumValue The input enum value, possibly as UPPERCASE
     * @return string|null The found enum value, or `null` if it doesn't exist
     */
    public function getEnumValueFromInput(string $inputEnumValue): ?string;
}
