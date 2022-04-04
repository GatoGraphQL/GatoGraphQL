<?php

class PoPTheme_Wassup_Volunteering_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_ADDONS,
            $this->getRoutesForAddons(...)
        );
    }

    public function getRoutesForAddons($routes)
    {
        $routes[] = POP_VOLUNTEERING_ROUTE_VOLUNTEER;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Volunteering_WebPlatform_PreloadHooks();
