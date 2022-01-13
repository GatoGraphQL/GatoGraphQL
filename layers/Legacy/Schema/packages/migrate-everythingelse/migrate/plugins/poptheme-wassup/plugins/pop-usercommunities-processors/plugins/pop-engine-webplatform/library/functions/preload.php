<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_UserCommunities_WebPlatform_PreloadHooks
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
        $routes[] = POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_UserCommunities_WebPlatform_PreloadHooks();
