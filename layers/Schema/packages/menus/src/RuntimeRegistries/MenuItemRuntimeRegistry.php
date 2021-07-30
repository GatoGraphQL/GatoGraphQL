<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RuntimeRegistries;

use PoPSchema\Menus\ObjectModels\MenuItem;

class MenuItemRuntimeRegistry implements MenuItemRuntimeRegistryInterface
{
    /** @var array<string|int,MenuItem> */
    protected array $menuItems = [];
    public function storeMenuItem(string | int $id, MenuItem $menuItem): void
    {
        $this->menuItems[$id] = $menuItem;
    }
    public function getMenuItem(string | int $id): ?MenuItem
    {
        return $this->menuItems[$id] ?? null;
    }
}
