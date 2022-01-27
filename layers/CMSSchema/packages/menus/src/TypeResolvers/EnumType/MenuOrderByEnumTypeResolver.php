<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Menus\Constants\MenuOrderBy;

class MenuOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MenuOrderBy::ID,
            MenuOrderBy::DATE,
            MenuOrderBy::NAME,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MenuOrderBy::ID => $this->__('Order by ID', 'menus'),
            MenuOrderBy::DATE => $this->__('Order by date', 'menus'),
            MenuOrderBy::NAME => $this->__('Order by name', 'menus'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
