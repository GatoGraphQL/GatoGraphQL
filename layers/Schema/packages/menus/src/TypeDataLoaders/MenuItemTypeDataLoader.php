<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;

class MenuItemTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        $ret = array_map(array($menuTypeAPI, 'getNavigationMenuItems'), $ids);
        return $ret;
    }
}
