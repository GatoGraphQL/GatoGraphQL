<?php

declare(strict_types=1);

namespace PoPSchema\MenusWP\TypeAPIs;

use WP_Menu;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
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
        $args = [];
        if ($options['return-type'] == ReturnTypes::IDS) {
            $args['fields'] = 'ids';
        }
        return wp_get_nav_menu_items($menu, $args);
    }
    public function getMenuItemTitle($menu_item) {

        return apply_filters('the_title', $menu_item->title, $menu_item->object_id);
    }

    public function getMenuObjectTermId($menu_object)
    {
        return $menu_object->term_id;
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
    public function getMenuTermId($menu)
    {
        return $menu->term_id;
    }
}
