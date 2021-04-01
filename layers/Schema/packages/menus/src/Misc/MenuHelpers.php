<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Misc;

use PoPSchema\Menus\Facades\MenuTypeAPIFacade;

class MenuHelpers
{
    public static function getMenuIDFromMenuName($menu): ?string
    {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        if ($menu_object = $menuTypeAPI->getNavigationMenuObject($menu)) {
            return $menuTypeAPI->getMenuObjectTermId($menu_object);
        }
        return null;
    }
}
