<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;

use function add_menu_page;

/**
 * Main plugin's admin menu
 */
class PluginMenu extends AbstractMenu
{
    private ?UserAuthorizationInterface $userAuthorization = null;

    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }

    public function getName(): string
    {
        return PluginApp::getMainPlugin()->getPluginNamespace();
    }

    public function addMenuPage(): void
    {
        $menuName = $this->getMenuName();
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();
        add_menu_page(
            $menuName,
            $menuName,
            $schemaEditorAccessCapability,
            $this->getName(),
            function (): void {
            },
            $this->getPluginIconSVG()
        );
    }

    public function getMenuName(): string
    {
        return PluginApp::getMainPlugin()->getPluginMenuName();
    }

    protected function getPluginIconSVG(): string
    {
        return PluginApp::getMainPlugin()->getPluginIconSVG();
    }
}
