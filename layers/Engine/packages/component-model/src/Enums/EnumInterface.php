<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

interface EnumInterface
{
    // These 5 values are calculated, based on the other ones
    public function getName(): string;
    public function getValues(): array;
    public function getNamespace(): string;
    public function getNamespacedName(): string;
    public function getMaybeNamespacedName(): string;
    public function getCoreValues(): ?array;
    public function getCoreValue(string $enumValue): ?string;
    public function getDescriptions(): array;
}
