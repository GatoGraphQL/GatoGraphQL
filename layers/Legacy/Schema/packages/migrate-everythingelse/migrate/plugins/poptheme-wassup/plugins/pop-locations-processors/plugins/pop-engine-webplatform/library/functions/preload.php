<?php

class PoPTheme_Wassup_Locations_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_MODALS,
            $this->getRoutesForModals(...)
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
