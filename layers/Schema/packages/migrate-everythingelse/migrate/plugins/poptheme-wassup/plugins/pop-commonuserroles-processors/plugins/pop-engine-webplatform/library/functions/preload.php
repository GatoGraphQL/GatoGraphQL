<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_CommonUserRoles_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_MAIN,
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
