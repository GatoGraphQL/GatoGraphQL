<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected MenuTypeAPIInterface $menuTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjects(array $ids): array
    {
        // If the menu doesn't exist, remove the `null` entry
        return array_filter(array_map(
            fn (string | int $id) => $this->menuTypeAPI->getMenu($id),
            $ids
        ));
    }
}
