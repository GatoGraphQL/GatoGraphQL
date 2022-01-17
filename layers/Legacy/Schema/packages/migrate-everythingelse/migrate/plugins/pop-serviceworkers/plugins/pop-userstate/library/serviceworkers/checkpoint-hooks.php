<?php

class PoP_ServiceWorkers_UserState_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:exclude:partial',
            array($this, 'getExcludedPartialpaths'),
            10,
            2
        );

        $resourceType = 'json';
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:partial',
            array($this, 'getNetworkfirstJsonPartialpaths')
        );

        // \PoP\Root\App::addFilter(
        //     'popSwAddEtagHeader',
        //     array($this, 'addEtagHeader')
        // );
    }

    // function addEtagHeader($add) {

    //     // If this is a silent page, then no need to add the etag, because none of the silent pages has strategy Cache First
    //     // function popSwAddEtagHeader() already addresses !PoP_UserState_Utils::currentRouteRequiresUserState(), however
    //     // this doesn't include pages POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS and POP_POSTS_ROUTE_LOADERS_POSTS_FIELDS, etc, which
    //     // do not require the user state, however they don't need the ETag either
    //     $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();
    //     if ($add && $settingsmanager->silentDocument()) {

    //         return false;
    //     }
        
    //     return $add;
    // }

    public function getNetworkfirstJsonPartialpaths($paths)
    {
        
        // It is basically all pages configured as silentDocument which are not appshell and do not have any checkpoint (as in, no user state required)
        // Eg: POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS and all the other loaders
        // It is so because these pages are loaded in the background, and are secondary (eg: loading comments),
        // so there's no need to show the stuff immediately to the user, when it might be stale. Instead, go get the
        // latest data from the server, show cached data only if the user has no connection
        $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();
        $userstatesettingsmanager = \PoPCMSSchema\UserState\Settings\SettingsManagerFactory::getInstance();
        foreach (\PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getRoutes() as $route) {
            // Remove those pages which are silent but not appshell, and then filter all the pages which have no checkpoints
            if ($settingsmanager->silentDocument($route)
                && !$settingsmanager->isAppshell($route)
                && $userstatesettingsmanager->requiresUserState($route)
            ) {
                $paths[] = $route.'/';
            }
        }
        
        return $paths;
    }

    public function getExcludedPartialpaths($excluded, $resourceType)
    {
        
        // Exclude all the dynamic pages: those needing user state
        if ($resourceType == 'json') {
            $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();
            $userstatesettingsmanager = \PoPCMSSchema\UserState\Settings\SettingsManagerFactory::getInstance();
            foreach (\PoP\ComponentModel\Settings\SettingsProcessorManagerFactory::getInstance()->getRoutes() as $route) {
                // Skip if it is an internal page, we don't want to expose it
                if ($settingsmanager->isForInternalUse($route)) {
                    continue;
                }

                if ($userstatesettingsmanager->requiresUserState($route)) {
                    $excluded[] = $route.'/';
                }
            }
        }
        
        return $excluded;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_UserState_Hooks();
