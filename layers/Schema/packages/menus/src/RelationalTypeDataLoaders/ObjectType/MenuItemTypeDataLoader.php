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
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
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
