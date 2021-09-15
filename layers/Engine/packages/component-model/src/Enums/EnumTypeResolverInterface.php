<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface EnumTypeResolverInterface extends TypeResolverInterface
{
    /**
     * @return string[]
     */
    public function getValues(): array;
    /**
     * @return array<string,string> Key: enum, Value: description
     */
    public function getDescriptions(): array;
    public function outputEnumNameInUppercase(): bool;
    public function getCoreValues(): ?array;
    public function getCoreValue(string $enumValue): ?string;
}
