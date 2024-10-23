<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;

/**
 * Main plugin's admin menu
 */
class PluginMenu extends AbstractMenu
{
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
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
        return 'gatographql';
    }

    public function addMenuPage(): void
    {
        $menuName = PluginApp::getMainPlugin()->getPluginName();
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();
        \add_menu_page(
            $menuName,
            $menuName,
            $schemaEditorAccessCapability,
            $this->getName(),
            function (): void {
            },
            $this->getPluginIconSVG()
        );
    }

    protected function getPluginIconSVG(): string
    {
        return 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkdyYXBoUUxfTG9nbyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4Ig0KCSB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MDAgNDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA0MDAgNDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjEyMiIgeT0iLTAuNCIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuODY2IC0wLjUgMC41IC0wLjg2NiAxNjMuMzE5NiAzNjMuMzEzNikiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjMyMC4zIi8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJPHJlY3QgeD0iMzkuOCIgeT0iMjcyLjIiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIzMjAuMyIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMzcuOSIgeT0iMzEyLjIiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjg2NiAtMC41IDAuNSAtMC44NjYgODMuMDY5MyA2NjMuMzQwOSkiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxODUiIGhlaWdodD0iMTYuNiIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjE3Ny4xIiB5PSI3MS4xIiB0cmFuc2Zvcm09Im1hdHJpeCgtMC44NjYgLTAuNSAwLjUgLTAuODY2IDQ2My4zNDA5IDI4My4wNjkzKSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE4NSIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMTIyLjEiIHk9Ii0xMyIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuNSAtMC44NjYgMC44NjYgLTAuNSAxMjYuNzkwMyAyMzIuMTIyMSkiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjE4NSIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjEwOS42IiB5PSIxNTEuNiIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuNSAtMC44NjYgMC44NjYgLTAuNSAyNjYuMDgyOCA0NzMuMzc2NikiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIzMjAuMyIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJPHJlY3QgeD0iNTIuNSIgeT0iMTA3LjUiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjE4NSIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCTxyZWN0IHg9IjMzMC45IiB5PSIxMDcuNSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE2LjYiIGhlaWdodD0iMTg1Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMjYyLjQiIHk9IjI0MC4xIiB0cmFuc2Zvcm09Im1hdHJpeCgtMC41IC0wLjg2NiAwLjg2NiAtMC41IDEyNi43OTUzIDcxNC4yODc1KSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE0LjUiIGhlaWdodD0iMTYwLjkiLz4NCgkJPC9nPg0KCTwvZz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNMzY5LjUsMjk3LjljLTkuNiwxNi43LTMxLDIyLjQtNDcuNywxMi44Yy0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuN2M5LjYtMTYuNywzMS0yMi40LDQ3LjctMTIuOA0KCQlDMzczLjUsMjU5LjksMzc5LjIsMjgxLjIsMzY5LjUsMjk3LjkiLz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNOTAuOSwxMzdjLTkuNiwxNi43LTMxLDIyLjQtNDcuNywxMi44Yy0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuN2M5LjYtMTYuNywzMS0yMi40LDQ3LjctMTIuOA0KCQlDOTQuOCw5OSwxMDAuNSwxMjAuMyw5MC45LDEzNyIvPg0KCTxwYXRoIGZpbGw9IiNFNTM1QUIiIGQ9Ik0zMC41LDI5Ny45Yy05LjYtMTYuNy0zLjktMzgsMTIuOC00Ny43YzE2LjctOS42LDM4LTMuOSw0Ny43LDEyLjhjOS42LDE2LjcsMy45LDM4LTEyLjgsNDcuNw0KCQlDNjEuNCwzMjAuMyw0MC4xLDMxNC42LDMwLjUsMjk3LjkiLz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNMzA5LjEsMTM3Yy05LjYtMTYuNy0zLjktMzgsMTIuOC00Ny43YzE2LjctOS42LDM4LTMuOSw0Ny43LDEyLjhjOS42LDE2LjcsMy45LDM4LTEyLjgsNDcuNw0KCQlDMzQwLjEsMTU5LjQsMzE4LjcsMTUzLjcsMzA5LjEsMTM3Ii8+DQoJPHBhdGggZmlsbD0iI0U1MzVBQiIgZD0iTTIwMCwzOTUuOGMtMTkuMywwLTM0LjktMTUuNi0zNC45LTM0LjljMC0xOS4zLDE1LjYtMzQuOSwzNC45LTM0LjljMTkuMywwLDM0LjksMTUuNiwzNC45LDM0LjkNCgkJQzIzNC45LDM4MC4xLDIxOS4zLDM5NS44LDIwMCwzOTUuOCIvPg0KCTxwYXRoIGZpbGw9IiNFNTM1QUIiIGQ9Ik0yMDAsNzRjLTE5LjMsMC0zNC45LTE1LjYtMzQuOS0zNC45YzAtMTkuMywxNS42LTM0LjksMzQuOS0zNC45YzE5LjMsMCwzNC45LDE1LjYsMzQuOSwzNC45DQoJCUMyMzQuOSw1OC40LDIxOS4zLDc0LDIwMCw3NCIvPg0KPC9nPg0KPC9zdmc+DQo=';
    }
}
