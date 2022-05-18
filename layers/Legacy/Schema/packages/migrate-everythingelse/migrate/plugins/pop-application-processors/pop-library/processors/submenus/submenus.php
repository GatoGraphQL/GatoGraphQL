<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Routing\Routes as RoutingRoutes;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Module_Processor_CustomSubMenus extends PoP_Module_Processor_SubMenusBase
{
    public final const COMPONENT_SUBMENU_AUTHOR = 'submenu-author';
    public final const COMPONENT_SUBMENU_TAG = 'submenu-tag';
    public final const COMPONENT_SUBMENU_SINGLE = 'submenu-single';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMENU_AUTHOR],
            [self::class, self::COMPONENT_SUBMENU_TAG],
            [self::class, self::COMPONENT_SUBMENU_SINGLE],
        );
    }
    public function getClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_AUTHOR:
            case self::COMPONENT_SUBMENU_TAG:
            case self::COMPONENT_SUBMENU_SINGLE:
                return 'btn btn-default btn-sm';
        }

        return parent::getClass($component);
    }
    public function getXsClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_AUTHOR:
            case self::COMPONENT_SUBMENU_TAG:
            case self::COMPONENT_SUBMENU_SINGLE:
                return 'btn btn-default btn-sm btn-block';
        }

        return parent::getClass($component);
    }
    public function getDropdownClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_AUTHOR:
            case self::COMPONENT_SUBMENU_TAG:
            case self::COMPONENT_SUBMENU_SINGLE:
                return 'btn-default';
        }

        return parent::getDropdownClass($component);
    }

    public function getRoutes(array $component, array &$props)
    {
        $ret = parent::getRoutes($component, $props);

        // Potentially, add an extra header level if the current page is one of the subheaders
        $route = \PoP\Root\App::getState('route');

        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_AUTHOR:
                $ret[RoutingRoutes::$MAIN] = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:author:mainsubheaders',
                    array(
                        POP_ROUTE_DESCRIPTION,                    )
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                // Allow for the members tab to be added by User Role Editor plugin
                return \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:author:routes',
                    $ret
                );

            case self::COMPONENT_SUBMENU_TAG:
                $ret[RoutingRoutes::$MAIN] = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:tag:mainsubheaders',
                    array()
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                return \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:tag:routes',
                    $ret
                );

            case self::COMPONENT_SUBMENU_SINGLE:
                $ret[RoutingRoutes::$MAIN] = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:single:mainsubheaders',
                    array()
                );
                if (in_array($route, $ret[RoutingRoutes::$MAIN])) {
                    $ret[$route] = array();
                }

                $ret[POP_ROUTE_AUTHORS] = array();

                return \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomSubMenus:single:routes',
                    $ret
                );
        }

        return $ret;
    }

    public function getUrl(array $component, $route, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_AUTHOR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $url = RequestUtils::addRoute($url, $route);

                // Allow URE to add the Organization/Community content source attribute
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomSubMenus:getUrl:author', $url, $route, $author);

            case self::COMPONENT_SUBMENU_TAG:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                return RequestUtils::addRoute($url, $route);

            case self::COMPONENT_SUBMENU_SINGLE:
                $url = $customPostTypeAPI->getPermalink(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                return RequestUtils::addRoute($url, $route);
        }

        return parent::getUrl($component, $route, $props);
    }
}


