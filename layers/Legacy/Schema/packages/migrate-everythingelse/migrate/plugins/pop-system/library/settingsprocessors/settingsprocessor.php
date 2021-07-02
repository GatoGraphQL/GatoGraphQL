<?php

class PoPSystem_Module_SettingsProcessor extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    // use PoPSystem_Module_SettingsProcessor_Trait;
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_BUILD,
                POP_SYSTEM_ROUTE_SYSTEM_GENERATE,
                POP_SYSTEM_ROUTE_SYSTEM_INSTALL,
            )
        );
    }

    public function isForInternalUse()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD => true,
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE => true,
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
        );
    }
}

/**
 * Initialization
 */
new PoPSystem_Module_SettingsProcessor();
