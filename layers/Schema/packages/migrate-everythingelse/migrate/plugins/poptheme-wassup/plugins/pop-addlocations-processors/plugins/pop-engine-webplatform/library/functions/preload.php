<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_AddLocations_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_MODALS,
            array($this, 'getRoutesForModals')
        );
    }

    public function getRoutesForModals($routes)
    {
        $routes[] = POP_ADDLOCATIONS_ROUTE_ADDLOCATION;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AddLocations_WebPlatform_PreloadHooks();
