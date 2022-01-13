<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_WebPlatform_UserLogin_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            array($this, 'getManagerDependencies')
        );

        HooksAPIFacade::getInstance()->addFilter(
            'PoP_SPAResourceLoader_FileReproduction_InitialResourcesConfig:routes',
            array($this, 'getInitialRoutes')
        );
    }

    public function getManagerDependencies($dependencies)
    {

        // User logged-in styles
        $dependencies[] = [PoP_CoreProcessors_DynamicCSSResourceLoaderProcessor::class, PoP_CoreProcessors_DynamicCSSResourceLoaderProcessor::RESOURCE_CSS_USERLOGGEDIN];
        return $dependencies;
    }

    public function getInitialRoutes($routes)
    {
        $routes[] = POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_WebPlatform_UserLogin_ResourceLoaderProcessor_Hooks();
