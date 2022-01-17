<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\RuntimeRegistries;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;

class MenuItemRuntimeRegistry implements MenuItemRuntimeRegistryInterface
{
    /** @var array<string|int,MenuItem> */
    protected array $menuItems = [];

    /** @var array<string|int,array<string|int,MenuItem>> */
    protected array $menuItemsByParent = [];

    public function storeMenuItem(MenuItem $menuItem): void
    {
        $this->menuItems[$menuItem->id] = $menuItem;
        // Only store MenuItems which have a parent
        // Those who do not have already been accessed in the Menu's "items" field
        if ($menuItem->parentID !== null) {
            $this->menuItemsByParent[$menuItem->parentID][$menuItem->id] = $menuItem;
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
