<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;

class MenuTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        // If the menu doesn't exist, remove the `null` entry
        return array_filter(array_map(
            /**
             * Commented temporarily until Rector can downgrade union types on anonymous functions
             * @see https://github.com/rectorphp/rector/issues/5989
             */
            // fn (string | int $id) => $menuTypeAPI->getMenu($id),
            fn ($id) => $menuTypeAPI->getMenu($id),
            $ids
        ));
    }
}
