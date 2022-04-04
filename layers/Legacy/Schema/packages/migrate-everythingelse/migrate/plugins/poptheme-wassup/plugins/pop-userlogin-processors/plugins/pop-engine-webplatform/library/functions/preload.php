<?php

class PoPTheme_Wassup_UserLogin_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            $this->getRoutesForMain(...)
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
