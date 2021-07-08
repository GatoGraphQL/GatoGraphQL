<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;
use WP_Post;

class MenuItemTypeAPI implements MenuItemTypeAPIInterface
{
    /**
     * MenuItem is the CPT 'nav_menu_item'
     * @see https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/#source
     */
    public function getMenuItem(string | int $id): ?object
    {
        /** @var WP_Post|null */
        return get_post($id, ARRAY_A);
    }
    public function getMenuItemID(object $menuItem): string | int
    {
        return $menuItem->ID;
    }
    public function getMenuItemTitle(object $menuItem): string
    {
        return apply_filters('the_title', $menuItem->title, $menuItem->object_id);
    }
    public function getMenuItemObjectID(object $menuItem): string | int
    {
        return $menuItem->object_id;
    }
    public function getMenuItemURL(object $menuItem): string
    {
        return $menuItem->url;
    }
    /**
     * @return string[]
     */
    public function getMenuItemClasses(object $menuItem): array
    {
        return $menuItem->classes;
    }
    public function getMenuItemParentID(object $menuItem): string | int | null
    {
        /**
         * If it has no parent, it has ID "0"
         */
        if ($menuItem->menu_item_parent === "0") {
            return null;
        }
        return $menuItem->menu_item_parent;
    }
    public function getMenuItemTarget(object $menuItem): string
    {
        return $menuItem->target;
    }
    public function getMenuItemDescription(object $menuItem): string
    {
        return $menuItem->description;
    }
}
