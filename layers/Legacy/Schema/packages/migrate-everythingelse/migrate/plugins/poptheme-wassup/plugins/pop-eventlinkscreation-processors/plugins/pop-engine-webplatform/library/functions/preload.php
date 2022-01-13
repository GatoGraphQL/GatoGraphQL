<?php

class PoPTheme_Wassup_EventLinksCreation_WebPlatform_PreloadHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'wassup:extra-routes:initialframes:'.\PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
            array($this, 'maybeGetRoutesForMain')
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'wassup:extra-routes:initialframes:'.POP_TARGET_ADDONS,
            array($this, 'maybeGetRoutesForAddons')
        );
    }

    public function maybeGetRoutesForMain($routes)
    {
        if (PoP_Application_Utils::getAddcontentTarget() == \PoP\ConfigurationComponentModel\Constants\Targets::MAIN) {
            $routes[] = POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK;
        }
        return $routes;
    }

    public function maybeGetRoutesForAddons($routes)
    {
        if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
            $routes[] = POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK;
        }
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EventLinksCreation_WebPlatform_PreloadHooks();
