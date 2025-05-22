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
    use LogCountBadgeMenuTrait;

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

        $menuNameTitle = $menuName;
        $logCountBadge = $this->getLogCountBadge();
        if ($logCountBadge !== null) {
            $menuNameTitle .= ' ' . $logCountBadge;
        }

        add_menu_page(
            $menuName,
            $menuNameTitle,
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
