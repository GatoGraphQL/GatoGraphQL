<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemObjectTypeDataLoader;

class MenuItemObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MenuItemObjectTypeDataLoader $menuItemObjectTypeDataLoader = null;

    final public function setMenuItemObjectTypeDataLoader(MenuItemObjectTypeDataLoader $menuItemObjectTypeDataLoader): void
    {
        $this->menuItemObjectTypeDataLoader = $menuItemObjectTypeDataLoader;
    }
    final protected function getMenuItemObjectTypeDataLoader(): MenuItemObjectTypeDataLoader
    {
        /** @var MenuItemObjectTypeDataLoader */
        return $this->menuItemObjectTypeDataLoader ??= $this->instanceManager->getInstance(MenuItemObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'MenuItem';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Items (links, pages, etc) added to a menu', 'menus');
    }

    public function getID(object $object): string|int|null
    {
        /** @var MenuItem */
        $menuItem = $object;
        return $menuItem->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuItemObjectTypeDataLoader();
    }
}
