<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\MenuMutations\Enums\MenuItemType;

class MenuItemTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MenuItemTypeEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MenuItemType::CUSTOM,
            MenuItemType::POST_TYPE,
            MenuItemType::TAXONOMY,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MenuItemType::CUSTOM => $this->__('Custom link menu item', 'menu-mutations'),
            MenuItemType::POST_TYPE => $this->__('Menu item linking to a post type object', 'menu-mutations'),
            MenuItemType::TAXONOMY => $this->__('Menu item linking to a taxonomy term', 'menu-mutations'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
