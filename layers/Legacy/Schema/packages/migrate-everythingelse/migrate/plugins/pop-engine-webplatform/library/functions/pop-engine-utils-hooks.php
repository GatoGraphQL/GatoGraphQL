<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Routing\RouteNatures;

class PoP_WebPlatformEngine_UtilsHooks
{
    /**
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        // Comment Leo 19/11/2017: when first loading the website, ask if we are using the AppShell before anything else,
        // in which case it will always be 'page' and the $post->ID set to the corresponding AppShell page ID
        if (RequestUtils::loadingSite() && PoP_WebPlatform_ServerUtils::useAppshell()) {

            // Comment Leo 19/11/2017: page ID POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL must be set at this plugin level, not on pop-serviceworkers
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            $vars = &$vars_in_array[0];
            $vars['nature'] = RouteNatures::GENERIC;//PageRouteNatures::PAGE;
            $vars['route'] = POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL;
        }
    }
    public static function getQueriedObject(array $queriedObjectList)
    {
        // Comment Leo 19/11/2017: when first loading the website, ask if we are using the AppShell before anything else,
        // in which case it will always be 'page' and the $post->ID set to the corresponding AppShell page ID
        if (RequestUtils::loadingSite() && PoP_WebPlatform_ServerUtils::useAppshell()) {

            return [
                null,
                null
            ];
        }

        return $queriedObjectList;
    }
}

/**
 * Initialization
 */
\PoP\Root\App::addAction('ApplicationState:addVars', array(PoP_WebPlatformEngine_UtilsHooks::class, 'addVars'), 1, 1); // Priority 1: execute immediately after PoP_Application_Engine_Utils, which has priority 0
\PoP\Root\App::addFilter('ApplicationState:queried-object', array(PoP_WebPlatformEngine_UtilsHooks::class, 'getQueriedObject'));
