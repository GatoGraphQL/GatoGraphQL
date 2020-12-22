<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;

class MenuItemTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $cmsmenusapi = \PoPSchema\Menus\FunctionAPIFactory::getInstance();
        $ret = array_map(array($cmsmenusapi, 'getNavigationMenuItems'), $ids);
        return $ret;
    }
}
