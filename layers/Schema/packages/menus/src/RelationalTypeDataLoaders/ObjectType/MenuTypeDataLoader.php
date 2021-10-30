<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class MenuTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?MenuTypeAPIInterface $menuTypeAPI = null;

    public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }

    public function getObjects(array $ids): array
    {
        // If the menu doesn't exist, remove the `null` entry
        return array_filter(array_map(
            fn (string | int $id) => $this->getMenuTypeAPI()->getMenu($id),
            $ids
        ));
    }
}
