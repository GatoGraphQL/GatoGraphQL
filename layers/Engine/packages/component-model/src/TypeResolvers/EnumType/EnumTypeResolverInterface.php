<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface EnumTypeResolverInterface extends ConcreteTypeResolverInterface, InputTypeResolverInterface
{
    /**
     * The values in the enum
     *
     * @return string[]
     */
    public function getEnumValues(): array;
    /**
     * Description for all enum values (which have a description)
     *
     * @return array<string,string> Key: enum value, Value: description
     */
    public function getEnumValueDescriptions(): array;
    /**
     * Description for a specific enum value
     */
    public function getEnumValueDescription(string $enumValue): ?string;
    /**
     * Deprecation message for all enum values (which are deprecated)
     *
     * @return array<string,string> Key: enum value, Value: deprecation message
     */
    public function getEnumValueDeprecationMessages(): array;
    /**
     * Deprecation message for a specific enum value
     */
    public function getEnumValueDeprecationMessage(string $enumValue): ?string;
}
