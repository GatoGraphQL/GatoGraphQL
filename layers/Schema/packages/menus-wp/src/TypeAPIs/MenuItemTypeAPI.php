<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;

class MenuItemTypeAPI implements MenuItemTypeAPIInterface
{
    /**
     * MenuItem is the CPT 'nav_menu_item'
     * @see https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/#source
     */
    public function getMenuItem(string | int $id): ?object
    {
        return get_post($id, ARRAY_A);
    }

    public function getMenuItemTitle($menu_item) {

        return apply_filters('the_title', $menu_item->title, $menu_item->object_id);
    }
    // public function getMenuItemTitle($menu_item)
    // {
    //     return $menu_item->title;
    // }
    public function getMenuItemObjectId($menu_item)
    {
        return $menu_item->object_id;
    }
    public function getMenuItemUrl($menu_item)
    {
        return $menu_item->url;
    }
    public function getMenuItemClasses($menu_item)
    {
        return $menu_item->classes;
    }
    public function getMenuItemId($menu_item)
    {
        return $menu_item->ID;
    }
    public function getMenuItemParent($menu_item)
    {
        return $menu_item->menu_item_parent;
    }
    public function getMenuItemTarget($menu_item)
    {
        return $menu_item->target;
    }
    public function getMenuItemDescription($menu_item)
    {
        return $menu_item->description;
    }
}
