<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    final protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        if ($this->menuTypeAPI === null) {
            /** @var MenuTypeAPIInterface */
            $menuTypeAPI = $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
            $this->menuTypeAPI = $menuTypeAPI;
        }
        return $this->menuTypeAPI;
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
