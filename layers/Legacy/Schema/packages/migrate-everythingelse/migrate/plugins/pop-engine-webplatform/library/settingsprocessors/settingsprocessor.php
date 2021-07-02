<?php

class PoPTheme_Wassup_PoPSW_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL,
        ));
    }

    public function silentDocument()
    {
        return array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => true,
        );
    }

    public function isAppshell()
    {
        return array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => true,
        );
    }

    // Comment Leo 14/11/2017: no need to comment it anymore, since making the AppShell always be loading using 'resource',
    // Then we don't need to obtain its bundle(group) files, we just load all individual resources directly
    // // Comment Leo 13/11/2017: The AppShell page cannot be marked as for internal use, or
    // // we won't be able to generate the bundle(group)s to load it
    public function isForInternalUse()
    {
        return array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => true,
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_PoPSW_Module_SettingsProcessor();
