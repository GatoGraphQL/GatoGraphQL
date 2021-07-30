<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
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
    /**
     * @return MenuItem[]
     */
    public function getMenuItems(string | int | object $menuObjectOrID): array
    {
        $menuItems = wp_get_nav_menu_items($menuObjectOrID);
        if ($menuItems === false) {
            return [];
        }
        /**
         * Convert from the object returned by `wp_get_nav_menu_items` to ObjectModels\MenuItem
         */
        return array_map(
            function (object $menuItem): MenuItem {
                return new MenuItem(
                    $menuItem->ID,
                    $menuItem->object_id,
                    $menuItem->menu_item_parent === "0" ? null : $menuItem->menu_item_parent,
                    \apply_filters('the_title', $menuItem->title, $menuItem->object_id),
                    $menuItem->url,
                    $menuItem->description,
                    \apply_filters('menuitem:classes', array_filter($menuItem->classes), $menuItem),
                    $menuItem->target,
                );
            },
            $menuItems
        );
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

    /**
     * @param array<string, mixed> $options
     * @return array<string|int|object>
     */
    public function getMenus(array $options = []): array
    {
        $args = [];
        $return_type = $options['return-type'] ?? null;
        if ($return_type == ReturnTypes::IDS) {
            // @see https://developer.wordpress.org/reference/classes/wp_term_query/get_terms/#description
            $args['fields'] = 'ids';
        }
        return \wp_get_nav_menus($args);
    }
}
