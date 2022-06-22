<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

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

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        // If the menu doesn't exist, remove the `null` entry
        return array_values(array_filter(array_map(
            $this->getMenuTypeAPI()->getMenu(...),
            $ids
        )));
    }
}
