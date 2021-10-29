<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use Symfony\Contracts\Service\Attribute\Required;

class MenuItemTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry = null;

    public function setMenuItemRuntimeRegistry(MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry): void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    protected function getMenuItemRuntimeRegistry(): MenuItemRuntimeRegistryInterface
    {
        return $this->menuItemRuntimeRegistry ??= $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }

    //#[Required]
    final public function autowireMenuItemTypeDataLoader(
        MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
    ): void {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
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
