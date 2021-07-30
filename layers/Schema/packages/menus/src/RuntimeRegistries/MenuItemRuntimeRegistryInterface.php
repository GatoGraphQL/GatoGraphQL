<?php

declare(strict_types=1);

namespace PoPSchema\Menus\RuntimeRegistries;

use PoPSchema\Menus\ObjectModels\MenuItem;

interface MenuItemRuntimeRegistryInterface
{
    public function storeMenuItem(string | int $id, MenuItem $menuItem): void;
    public function getMenuItem(string | int $id): ?MenuItem;
}
