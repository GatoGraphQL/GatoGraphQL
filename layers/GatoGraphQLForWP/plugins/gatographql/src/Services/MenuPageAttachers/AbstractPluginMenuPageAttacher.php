<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\Services\Menus\MenuInterface;
use GatoGraphQL\GatoGraphQL\Services\Menus\PluginMenu;

/**
 * Admin menu class
 */
abstract class AbstractPluginMenuPageAttacher extends AbstractMenuPageAttacher
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
