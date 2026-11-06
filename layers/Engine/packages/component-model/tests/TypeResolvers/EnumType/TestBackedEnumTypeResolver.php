<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

class TestBackedEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'TestBackedEnum';
    }

    public function getBackedEnumClass(): ?string
    {
        return TestBackedEnum::class;
    }

    public function getEnumValueDeprecationMessage(string $enumValue): ?string
    {
        return match (TestBackedEnum::tryFrom($enumValue)) {
            TestBackedEnum::SECOND => 'Use FIRST',
            default => parent::getEnumValueDeprecationMessage($enumValue),
        };
    }
}
