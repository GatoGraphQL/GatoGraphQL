<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_Wassup_AddHighlights_WebPlatform_PreloadHooks
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
        $routes[] = POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AddHighlights_WebPlatform_PreloadHooks();
