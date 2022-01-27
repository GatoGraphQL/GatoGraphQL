<?php
use PoP\ComponentModel\Misc\RequestUtils;

class PoP_ServiceWorkers_WebPlatform_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter('getEnqueuefileType', array($this, 'getEnqueuefileType'));
    }

    public function getEnqueuefileType($type)
    {

        // The AppShell, it must always use 'resource', or otherwise it will need to load extra bundle(group) files,
        // making the initial SW pre-fetch heavy, and not allowing to easily create the AppShell for the different thememodes (embed, print)
        // if (RequestUtils::isPage(POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL)) {
        if (RequestUtils::isRoute(POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL)) {
            return 'resource';
        }

        return $type;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_WebPlatform_ResourceLoader_Hooks();
