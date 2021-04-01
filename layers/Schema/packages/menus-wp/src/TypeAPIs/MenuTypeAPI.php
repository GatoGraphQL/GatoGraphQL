<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use WP_Menu;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use WP_Term;

class MenuTypeAPI implements MenuTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Menu
     */
    public function isInstanceOfMenuType(object $object): bool
    {
        return $object instanceof WP_Menu;
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

    public function getMenuTermId($menu)
    {
        return $menu->term_id;
    }

    public function getMenuIDFromMenuName(string $menuName): string | int | null
    {
        if ($menuObject = $this->getNavigationMenuObject($menuName)) {
            return $menuObject->term_id;
        }
        return null;
    }

    protected function getNavigationMenuObject(int $menuID): ?WP_Term
    {
        $locations = get_nav_menu_locations();
        $menu_object_id = $locations[$menuID];
        $menuObject = $this->getNavigationMenuObjectById($menu_object_id);
        if ($menuObject === false) {
            return null;
        }
        return $menuObject;
    }
}
