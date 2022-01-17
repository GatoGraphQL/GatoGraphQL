<?php

class PoP_AddHighlights_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_AddHighlights_UserState_Module_SettingsProcessor();
