<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_UserPlatform_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_MODALS,
            array($this, 'getRoutesForModals')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            array($this, 'getRoutesForMain')
        );
    }

    public function getRoutesForModals($routes)
    {
        $routes[] = POP_USERPLATFORM_ROUTE_INVITENEWUSERS;
        return $routes;
    }

    public function getRoutesForMain($routes)
    {
        $routes[] = POP_USERPLATFORM_ROUTE_SETTINGS;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_UserPlatform_WebPlatform_PreloadHooks();
