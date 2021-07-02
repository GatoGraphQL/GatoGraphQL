<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_GoogleAnalytics_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            array($this, 'getManagerDependencies')
        );
    }

    public function getManagerDependencies($dependencies)
    {

        // File ga-functions.js is not referenced by anyone, so we must explicitly add the dependency
        $dependencies[] = [PoP_GoogleAnalytics_ResourceLoaderProcessor::class, PoP_GoogleAnalytics_ResourceLoaderProcessor::RESOURCE_GAFUNCTIONS];
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_GoogleAnalytics_ResourceLoaderProcessor_Hooks();
