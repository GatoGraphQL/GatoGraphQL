<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use PoPSchema\Menus\TypeAPIs\MenuItemTypeAPIInterface;

class MenuItemTypeAPI implements MenuItemTypeAPIInterface
{
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
