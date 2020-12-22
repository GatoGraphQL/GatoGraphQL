<?php
namespace PoPSchema\Menus;

interface FunctionAPI
{
    public function getNavigationMenuObject($menu_id);
    public function getNavigationMenuObjectById($menu_object_id);
    public function getNavigationMenuItems($menu);
    public function getMenuItemTitle($menu_item);
}
