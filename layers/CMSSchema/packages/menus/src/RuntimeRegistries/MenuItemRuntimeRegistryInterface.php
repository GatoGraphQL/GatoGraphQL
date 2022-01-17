<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\RuntimeRegistries;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;

interface MenuItemRuntimeRegistryInterface
{
    public function storeMenuItem(MenuItem $menuItem): void;
    public function getMenuItem(string | int $id): ?MenuItem;
    /** @return array<string|int,MenuItem> */
    public function getMenuItemChildren(string | int $id): array;
}
