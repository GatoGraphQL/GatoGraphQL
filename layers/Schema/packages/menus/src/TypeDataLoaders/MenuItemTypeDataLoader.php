<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoPSchema\Menus\Facades\MenuItemTypeAPIFacade;

class MenuItemTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        return array_map(
            /**
             * Commented temporarily until Rector can downgrade union types on anonymous functions
             * @see https://github.com/rectorphp/rector/issues/5989
             */
            // fn (string | int $id) => $menuItemTypeAPI->getMenuItem($id),
            fn ($id) => $menuItemTypeAPI->getMenuItem($id),
            $ids
        );
    }
}
