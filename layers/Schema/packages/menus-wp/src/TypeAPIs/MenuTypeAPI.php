<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use WP_Term;

class MenuTypeAPI implements MenuTypeAPIInterface
{
    public function getMenu(string | int $menuID): ?object
    {
        $object = wp_get_nav_menu_object($menuID);
        // If the object is not found, it returns `false`. Return `null` instead
        if ($object === false) {
            return null;
        }
        return $object;
    }
    public function getMenuItemsData(string | int | object $menuObjectOrID): array
    {
        return wp_get_nav_menu_items($menuObjectOrID);
    }

    public function getMenuID(object $menu): string | int
    {
        return $menu->term_id;
    }

    public function getMenuIDFromMenuName(string $menuName): string | int | null
    {
        if ($menuObject = $this->getMenuObject($menuName)) {
            return $menuObject->term_id;
        }
        return null;
    }

    protected function getMenuObject(string $menuName): ?WP_Term
    {
        $locations = get_nav_menu_locations();
        $menuID = $locations[$menuName];
        return $this->getMenu($menuID);
    }
}
