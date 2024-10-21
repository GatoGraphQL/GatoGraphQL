<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;

trait WithSettingsPageMenuPageAttacherTrait
{
    protected function addSettingsMenuPage(): void
    {
        $menuName = $this->getMenuName();
        if (
            $hookName = \add_submenu_page(
                $menuName,
                __('Settings', 'gatographql'),
                __('Settings', 'gatographql'),
                'manage_options',
                $this->getSettingsMenuPage()->getScreenID(),
                [$this->getSettingsMenuPage(), 'print'],
            )
        ) {
            $this->getSettingsMenuPage()->setHookName($hookName);
        }
    }

    abstract protected function getMenuName(): string;
    abstract protected function getSettingsMenuPage(): SettingsMenuPage;
}
