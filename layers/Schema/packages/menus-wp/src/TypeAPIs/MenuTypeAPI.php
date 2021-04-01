<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use WP_Menu;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeAPI implements MenuTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Menu
     */
    public function isInstanceOfMenuType(object $object): bool
    {
        return $object instanceof WP_Menu;
    }

    public function getNavigationMenuObject($menu_id)
    {
        $locations = get_nav_menu_locations();
        $menu_object_id = $locations[$menu_id];
        return $this->getNavigationMenuObjectById($menu_object_id);
    }
    public function getNavigationMenuObjectById($menu_object_id)
    {
        $object = wp_get_nav_menu_object($menu_object_id);
        // If the object is not found, it returns `false`. Return `null` instead
        if ($object === false) {
            return null;
        }
        return $object;
    }
    public function getNavigationMenuItems($menu, $options = [])
    {
        return wp_get_nav_menu_items($menu);
    }

    public function getMenuObjectTermId($menu_object)
    {
        return $menu_object->term_id;
    }
    public function getMenuTermId($menu)
    {
        return $menu->term_id;
    }

    public function getMenuIDFromMenuName(string $menuName): string | int | null
    {
        if ($menu_object = $this->getNavigationMenuObject($menuName)) {
            return $this->getMenuObjectTermId($menu_object);
        }
        return null;
    }
}
