<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

class TestStringEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'TestStringEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            'FIRST',
            'SECOND',
        ];
    }
}
