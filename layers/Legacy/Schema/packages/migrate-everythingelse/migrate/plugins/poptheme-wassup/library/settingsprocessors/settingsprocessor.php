<?php
use PoP\Root\Routing\Routes as RoutingRoutes;

class PoPTheme_Wassup_Module_SettingsProcessor extends PoP_Module_SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                RoutingRoutes::$MAIN,
                POP_ROUTE_DESCRIPTION,
                POPTHEME_WASSUP_ROUTE_SUMMARY,
                POP_ROUTE_AUTHORS,
                POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES,
            )
        );
    }

    public function silentDocument()
    {
        return array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES => true,
        );
    }

    public function isAppshell()
    {
        return array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES => true,
        );
    }

    public function isFunctional()
    {
        return array(
            RoutingRoutes::$MAIN => true,
            POP_ROUTE_DESCRIPTION => true,
            POPTHEME_WASSUP_ROUTE_SUMMARY => true,
            POP_ROUTE_AUTHORS => true,
        );
    }

    public function storeLocal()
    {
        return array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES => true,
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Module_SettingsProcessor();
