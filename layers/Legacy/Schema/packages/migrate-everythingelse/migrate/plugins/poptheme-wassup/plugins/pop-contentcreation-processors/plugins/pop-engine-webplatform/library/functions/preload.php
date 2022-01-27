<?php

class PoPTheme_Wassup_ContentCreation_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_ADDONS,
            array($this, 'getRoutesForAddons')
        );
    }

    public function getRoutesForAddons($routes)
    {
        $routes[] = POP_CONTENTCREATION_ROUTE_FLAG;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_ContentCreation_WebPlatform_PreloadHooks();
