<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

class MenuItemTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry = null;

    final public function setMenuItemRuntimeRegistry(MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry): void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    final protected function getMenuItemRuntimeRegistry(): MenuItemRuntimeRegistryInterface
    {
        return $this->menuItemRuntimeRegistry ??= $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }

    public function getObjects(array $ids): array
    {
        // Retrieve each item from the dynamic registry
        return array_map(
            fn (string | int $id) => $this->getMenuItemRuntimeRegistry()->getMenuItem($id),
            $ids
        );
    }
}
