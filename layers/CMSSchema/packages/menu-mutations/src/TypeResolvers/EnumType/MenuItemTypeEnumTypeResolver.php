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

    public function getBackedEnumClass(): ?string
    {
        return MenuItemType::class;
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match (MenuItemType::tryFrom($enumValue)) {
            MenuItemType::CUSTOM => $this->__('Custom link menu item', 'gatographql'),
            MenuItemType::POST_TYPE => $this->__('Menu item linking to a post type object', 'gatographql'),
            MenuItemType::TAXONOMY => $this->__('Menu item linking to a taxonomy term', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
