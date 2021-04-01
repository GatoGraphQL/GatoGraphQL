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
        $ret = array_filter(array_map(array($menuTypeAPI, 'getNavigationMenuObjectById'), $ids));
        return $ret;
    }
}
