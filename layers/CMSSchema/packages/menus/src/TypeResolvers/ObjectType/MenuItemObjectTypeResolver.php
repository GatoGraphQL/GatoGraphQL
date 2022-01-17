<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuItemTypeDataLoader;

class MenuItemObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MenuItemTypeDataLoader $menuItemTypeDataLoader = null;

    final public function setMenuItemTypeDataLoader(MenuItemTypeDataLoader $menuItemTypeDataLoader): void
    {
        $this->menuItemTypeDataLoader = $menuItemTypeDataLoader;
    }
    final protected function getMenuItemTypeDataLoader(): MenuItemTypeDataLoader
    {
        return $this->menuItemTypeDataLoader ??= $this->instanceManager->getInstance(MenuItemTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'MenuItem';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Items (links, pages, etc) added to a menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        /** @var MenuItem */
        $menuItem = $object;
        return $menuItem->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuItemTypeDataLoader();
    }
}
