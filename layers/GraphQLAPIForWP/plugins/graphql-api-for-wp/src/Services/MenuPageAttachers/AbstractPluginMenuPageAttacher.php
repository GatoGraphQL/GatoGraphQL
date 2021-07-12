<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;

/**
 * Admin menu class
 */
abstract class AbstractPluginMenuPageAttacher extends AbstractMenuPageAttacher
{
    public function getMenuClass(): string
    {
        return PluginMenu::class;
    }
}
