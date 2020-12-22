<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Misc;

class MenuHelpers
{
    public static function getMenuIDFromMenuName($menu): ?string
    {
        $cmsmenusresolver = \PoPSchema\Menus\ObjectPropertyResolverFactory::getInstance();
        $cmsmenusapi = \PoPSchema\Menus\FunctionAPIFactory::getInstance();
        if ($menu_object = $cmsmenusapi->getNavigationMenuObject($menu)) {
            return $cmsmenusresolver->getMenuObjectTermId($menu_object);
        }
        return null;
    }
}
