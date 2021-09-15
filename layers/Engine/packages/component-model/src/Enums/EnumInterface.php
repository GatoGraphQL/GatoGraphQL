<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface EnumInterface extends TypeResolverInterface
{
    public function getValues(): array;
    public function getCoreValues(): ?array;
    public function getCoreValue(string $enumValue): ?string;
    public function getDescriptions(): array;
}
