<?php

class PoPSystem_WP_Module_SettingsProcessor extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    // use PoPSystem_WP_Module_SettingsProcessor_Trait;
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS,
            )
        );
    }

    public function isForInternalUse()
    {
        return array(
            POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS => true,
        );
    }

    public function getCheckpoints()
    {
        return array(
            POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,
        );
    }
}

/**
 * Initialization
 */
new PoPSystem_WP_Module_SettingsProcessor();
