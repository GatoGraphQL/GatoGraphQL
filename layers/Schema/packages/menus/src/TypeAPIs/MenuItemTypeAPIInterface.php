<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeAPIs;

interface MenuItemTypeAPIInterface
{
    public function getMenuItemID(object $menuItem): string | int;
    public function getMenuItemTitle(object $menuItem): string;
    public function getMenuItemObjectID(object $menuItem): string | int;
    public function getMenuItemURL(object $menuItem): string;
    /**
     * @return string[]
     */
    public function getMenuItemClasses(object $menuItem): array;
    public function getMenuItemParentID(object $menuItem): string | int | null;
    public function getMenuItemTarget(object $menuItem): string;
    public function getMenuItemDescription(object $menuItem): string;
}
