<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_UserLogin_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ComponentModel\Constants\Targets::MAIN,
            array($this, 'getRoutesForMain')
        );
    }

    public function getRoutesForMain($routes)
    {
        $routes[] = POP_USERLOGIN_ROUTE_LOGIN;
        $routes[] = POP_USERLOGIN_ROUTE_LOGOUT;
        $routes[] = POP_USERLOGIN_ROUTE_LOSTPWD;
        $routes[] = POP_USERLOGIN_ROUTE_LOSTPWDRESET;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_UserLogin_WebPlatform_PreloadHooks();
