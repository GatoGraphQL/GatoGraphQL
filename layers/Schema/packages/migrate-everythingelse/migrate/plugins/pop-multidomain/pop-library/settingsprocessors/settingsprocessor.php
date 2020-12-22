<?php

class PoPTheme_Wassup_MultiDomain_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_MULTIDOMAIN_ROUTE_EXTERNAL,
            )
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_MultiDomain_Module_SettingsProcessor();
