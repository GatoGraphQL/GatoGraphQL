<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Services\Menus\MenuInterface;
use GatoGraphQL\GatoGraphQL\Services\Menus\PluginMenu;

/**
 * Main plugin menu page
 */
abstract class AbstractPluginMenuPage extends AbstractMenuPage
{
    private ?PluginMenu $pluginMenu = null;

    final protected function getPluginMenu(): PluginMenu
    {
        if ($this->pluginMenu === null) {
            /** @var PluginMenu */
            $pluginMenu = $this->instanceManager->getInstance(PluginMenu::class);
            $this->pluginMenu = $pluginMenu;
        }
        return $this->pluginMenu;
    }

    public function getMenu(): MenuInterface
    {
        return $this->getPluginMenu();
    }
}
