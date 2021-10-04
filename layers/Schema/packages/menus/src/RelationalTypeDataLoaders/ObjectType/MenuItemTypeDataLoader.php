<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use Symfony\Contracts\Service\Attribute\Required;

class MenuItemTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry;

    #[Required]
    final public function autowireMenuItemTypeDataLoader(
        MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
    ): void {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }

    public function getObjects(array $ids): array
    {
        // Retrieve each item from the dynamic registry
        return array_map(
            fn (string | int $id) => $this->menuItemRuntimeRegistry->getMenuItem($id),
            $ids
        );
    }
}
