<?php

class PoPTheme_Wassup_Newsletter_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            array($this, 'getRoutesForMain')
        );
    }

    public function getRoutesForMain($routes)
    {
        $routes[] = POP_NEWSLETTER_ROUTE_NEWSLETTER;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Newsletter_WebPlatform_PreloadHooks();
