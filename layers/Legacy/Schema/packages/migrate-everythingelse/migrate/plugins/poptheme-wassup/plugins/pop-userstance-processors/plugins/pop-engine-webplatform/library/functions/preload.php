<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_UserStance_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_ADDONS,
            array($this, 'getRoutesForAddons')
        );
    }

    public function getRoutesForAddons($routes)
    {
        $routes[] = POP_USERSTANCE_ROUTE_ADDSTANCE;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_UserStance_WebPlatform_PreloadHooks();
