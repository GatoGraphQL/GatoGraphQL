<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RuntimeRegistries;

use PoPSchema\Menus\ObjectModels\MenuItem;

class MenuItemRuntimeRegistry implements MenuItemRuntimeRegistryInterface
{
    /** @var array<string|int,MenuItem> */
    protected array $menuItems = [];
    /** @var array<string|int,array<string|int,MenuItem>> */
    protected array $menuItemsByParent = [];

    public function storeMenuItem(MenuItem $menuItem): void
    {
        $this->menuItems[$menuItem->ID] = $menuItem;
        // Only store MenuItems which have a parent
        // Those who do not have already been accessed in the Menu's "items" field
        if ($menuItem->menu_item_parent !== null) {
            $this->menuItemsByParent[$menuItem->menu_item_parent][$menuItem->ID] = $menuItem;
        }
    }

    public function getMenuItem(string | int $id): ?MenuItem
    {
        return $this->menuItems[$id] ?? null;
    }

    /** @return array<string|int,MenuItem> */
    public function getMenuItemChildren(string | int $id): array
    {
        return $this->menuItemsByParent[$id] ?? [];
    }
}
