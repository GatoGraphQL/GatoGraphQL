<?php

class PoPTheme_Wassup_CommonUserRoles_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            array($this, 'getRoutesForMain')
        );
    }

    public function getRoutesForMain($routes)
    {
        $routes[] = POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION;
        $routes[] = POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_CommonUserRoles_WebPlatform_PreloadHooks();
