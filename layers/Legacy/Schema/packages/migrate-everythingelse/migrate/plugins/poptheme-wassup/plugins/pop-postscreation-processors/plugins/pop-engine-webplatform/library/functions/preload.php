<?php

class PoPTheme_Wassup_PostsCreation_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            $this->maybeGetRoutesForMain(...)
        );
        \PoP\Root\App::addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_ADDONS,
            $this->maybeGetRoutesForAddons(...)
        );
    }

    public function maybeGetRoutesForMain($routes)
    {
        if (PoP_Application_Utils::getAddcontentTarget() == \PoP\ConfigurationComponentModel\Constants\Targets::MAIN) {
            $routes[] = POP_POSTSCREATION_ROUTE_ADDPOST;
        }
        return $routes;
    }

    public function maybeGetRoutesForAddons($routes)
    {
        if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
            $routes[] = POP_POSTSCREATION_ROUTE_ADDPOST;
        }
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_PostsCreation_WebPlatform_PreloadHooks();
