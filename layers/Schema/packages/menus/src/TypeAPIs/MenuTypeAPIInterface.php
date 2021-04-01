<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MenuTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Menu
     */
    public function isInstanceOfMenuType(object $object): bool;

    public function getNavigationMenuObject($menu_id);
    public function getNavigationMenuObjectById($menu_object_id);
    public function getNavigationMenuItems($menu, $options = []);
    public function getMenuObjectTermId($menu_object);
    public function getMenuTermId($menu);
    public function getMenuIDFromMenuName(string $menuName);
}
