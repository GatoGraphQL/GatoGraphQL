<?php

class PoPTheme_Wassup_ContactUs_WebPlatform_PreloadHooks
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
        $routes[] = POP_CONTACTUS_ROUTE_CONTACTUS;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ContactUs_WebPlatform_PreloadHooks();
