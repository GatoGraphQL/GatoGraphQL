<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\LogsMenuPage;
use function add_menu_page;

/**
 * Main plugin's admin menu
 */
class PluginMenu extends AbstractMenu
{
    use LogCountBadgeMenuTrait;

    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?LogsMenuPage $logsMenuPage = null;

    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final protected function getLogsMenuPage(): LogsMenuPage
    {
        if ($this->logsMenuPage === null) {
            /** @var LogsMenuPage */
            $logsMenuPage = $this->instanceManager->getInstance(LogsMenuPage::class);
            $this->logsMenuPage = $logsMenuPage;
        }
        return $this->logsMenuPage;
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
        $logsMenuPage = $this->getLogsMenuPage();
        if ($logsMenuPage->isServiceEnabled()) {
            $logCountBadge = $this->getLogCountBadge();
            if ($logCountBadge !== null) {
                $menuNameTitle .= ' ' . $logCountBadge;
            }
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
