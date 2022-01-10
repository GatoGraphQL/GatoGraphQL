<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\Routes as RoutingRoutes;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_CustomSubMenus extends PoP_Module_Processor_SubMenusBase
{
    public const MODULE_SUBMENU_AUTHOR = 'submenu-author';
    public const MODULE_SUBMENU_TAG = 'submenu-tag';
    public const MODULE_SUBMENU_SINGLE = 'submenu-single';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMENU_AUTHOR],
            [self::class, self::MODULE_SUBMENU_TAG],
            [self::class, self::MODULE_SUBMENU_SINGLE],
        );
    }
    public function getClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_AUTHOR:
            case self::MODULE_SUBMENU_TAG:
            case self::MODULE_SUBMENU_SINGLE:
                return 'btn btn-default btn-sm';
        }

        return parent::getClass($module);
    }
    public function getXsClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_AUTHOR:
            case self::MODULE_SUBMENU_TAG:
            case self::MODULE_SUBMENU_SINGLE:
                return 'btn btn-default btn-sm btn-block';
        }

        return parent::getClass($module);
    }
    public function getDropdownClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_AUTHOR:
            case self::MODULE_SUBMENU_TAG:
            case self::MODULE_SUBMENU_SINGLE:
                return 'btn-default';
        }

        return parent::getDropdownClass($module);
    }

    public function getRoutes(array $module, array &$props)
    {
        $ret = parent::getRoutes($module, $props);

        // Potentially, add an extra header level if the current page is one of the subheaders
        $route = \PoP\Root\App::getState('route');

        switch ($module[1]) {
            case self::MODULE_SUBMENU_AUTHOR:
                $ret[RoutingRoutes::$MAIN] = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:author:mainsubheaders',
                    array(
                        POP_ROUTE_DESCRIPTION,                    )
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                // Allow for the members tab to be added by User Role Editor plugin
                return HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:author:routes',
                    $ret
                );

            case self::MODULE_SUBMENU_TAG:
                $ret[RoutingRoutes::$MAIN] = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:tag:mainsubheaders',
                    array()
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                return HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:tag:routes',
                    $ret
                );

            case self::MODULE_SUBMENU_SINGLE:
                $ret[RoutingRoutes::$MAIN] = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:single:mainsubheaders',
                    array()
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                $ret[POP_ROUTE_AUTHORS] = array();

                return HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:single:routes',
                    $ret
                );
        }

        return $ret;
    }

    public function getUrl(array $module, $route, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_SUBMENU_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $url = RequestUtils::addRoute($url, $route);

                // Allow URE to add the Organization/Community content source attribute
                return HooksAPIFacade::getInstance()->applyFilters('PoP_Module_Processor_CustomSubMenus:getUrl:author', $url, $route, $author);

            case self::MODULE_SUBMENU_TAG:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                return RequestUtils::addRoute($url, $route);

            case self::MODULE_SUBMENU_SINGLE:
                $url = $customPostTypeAPI->getPermalink(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                return RequestUtils::addRoute($url, $route);
        }

        return parent::getUrl($module, $route, $props);
    }
}


