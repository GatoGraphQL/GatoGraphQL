<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Admin menu class
 */
abstract class AbstractPluginMenuPageAttacher extends AbstractMenuPageAttacher
{
    protected PluginMenu $pluginMenu;

    #[Required]
    final public function autowireAbstractPluginMenuPageAttacher(
        PluginMenu $pluginMenu,
    ): void {
        $this->pluginMenu = $pluginMenu;
    }

    public function getMenu(): MenuInterface
    {
        return $this->pluginMenu;
    }
}
