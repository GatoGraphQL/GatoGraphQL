<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    final public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }

    public function getObjects(array $ids): array
    {
        // If the menu doesn't exist, remove the `null` entry
        return array_values(array_filter(array_map(
            fn (string | int $id) => $this->getMenuTypeAPI()->getMenu($id),
            $ids
        )));
    }
}
