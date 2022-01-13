<?php

class PoPTheme_Wassup_Locations_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_MODALS,
            array($this, 'getRoutesForModals')
        );
    }

    public function getRoutesForModals($routes)
    {
        $routes[] = POP_LOCATIONS_ROUTE_LOCATIONSMAP;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Locations_WebPlatform_PreloadHooks();
