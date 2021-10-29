<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Main plugin menu page
 */
abstract class AbstractPluginMenuPage extends AbstractMenuPage
{
    private ?PluginMenu $pluginMenu = null;

    public function setPluginMenu(PluginMenu $pluginMenu): void
    {
        $this->pluginMenu = $pluginMenu;
    }
    protected function getPluginMenu(): PluginMenu
    {
        return $this->pluginMenu ??= $this->instanceManager->getInstance(PluginMenu::class);
    }

    public function getMenu(): MenuInterface
    {
        return $this->getPluginMenu();
    }
}
