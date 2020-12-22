<?php
namespace PoPSchema\Menus;

interface ObjectPropertyResolver
{
    public function getMenuObjectTermId($menu_object);
    public function getMenuItemTitle($menu_item);
    public function getMenuItemObjectId($menu_item);
    public function getMenuItemUrl($menu_item);
    public function getMenuItemClasses($menu_item);
    public function getMenuItemId($menu_item);
    public function getMenuItemParent($menu_item);
    public function getMenuItemTarget($menu_item);
    public function getMenuItemDescription($menu_item);
    public function getMenuTermId($menu);
}
