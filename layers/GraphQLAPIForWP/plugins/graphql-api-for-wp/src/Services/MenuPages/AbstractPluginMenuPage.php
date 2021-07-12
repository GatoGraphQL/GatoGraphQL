<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;

/**
 * Main plugin menu page
 */
abstract class AbstractPluginMenuPage extends AbstractMenuPage
{
    public function getMenuClass(): string
    {
        return PluginMenu::class;
    }
}
