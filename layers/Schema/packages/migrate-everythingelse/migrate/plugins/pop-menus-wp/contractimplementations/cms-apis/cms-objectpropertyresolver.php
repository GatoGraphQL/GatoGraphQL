<?php
namespace PoPSchema\Menus\WP;

class ObjectPropertyResolver extends \PoPSchema\Menus\ObjectPropertyResolver_Base
{
    public function getMenuObjectTermId($menu_object)
    {
        return $menu_object->term_id;
    }
    public function getMenuItemTitle($menu_item)
    {
        return $menu_item->title;
    }
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
    public function getMenuTermId($menu)
    {
        return $menu->term_id;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
