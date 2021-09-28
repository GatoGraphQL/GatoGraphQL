<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;

class MenuItemTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry;
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
    ) {
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
