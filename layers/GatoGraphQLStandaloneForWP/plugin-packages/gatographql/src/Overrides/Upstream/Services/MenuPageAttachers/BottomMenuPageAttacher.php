<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers\BottomMenuPageAttacher as UpstreamBottomMenuPageAttacher;

class BottomMenuPageAttacher extends UpstreamBottomMenuPageAttacher
{
    /**
     * Standalone plugins: The Settings page needs capability
     * "manage_options". If the current user does not fulfil it,
     * and this page was added as the first item,
     * then WordPress will still add a first entry with the same
     * name as the plugin. Remove that entry from the menu!
     */
    public function addMenuPages(): void
    {
        parent::addMenuPages();

        global $submenu;
        $menuName = $this->getMenuName();
        if ($submenu[$menuName][0][0] === $this->getPluginMenu()->getMenuName()) {
            unset($submenu[$menuName][0]);
        }
    }
}
