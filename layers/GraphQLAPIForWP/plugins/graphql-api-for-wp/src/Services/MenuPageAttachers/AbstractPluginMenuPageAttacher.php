<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\Menu;

/**
 * Admin menu class
 */
abstract class AbstractPluginMenuPageAttacher extends AbstractMenuPageAttacher
{
    public function getMenuClass(): string
    {
        return Menu::class;
    }
}
