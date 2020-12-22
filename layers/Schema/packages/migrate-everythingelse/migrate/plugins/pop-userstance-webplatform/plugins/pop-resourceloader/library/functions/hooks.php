<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_UserStance_ResourceLoaderProcessor_Hooks
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
        $dependencies[] = [PoP_Application_UserStance_CSSResourceLoaderProcessor::class, PoP_Application_UserStance_CSSResourceLoaderProcessor::RESOURCE_CSS_VOTINGSTYLES];
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_Application_UserStance_ResourceLoaderProcessor_Hooks();
