<?php
namespace PoPSchema\Menus\WP;

use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class FunctionAPI extends \PoPSchema\Menus\FunctionAPI_Base
{
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
}

/**
 * Initialize
 */
new FunctionAPI();
