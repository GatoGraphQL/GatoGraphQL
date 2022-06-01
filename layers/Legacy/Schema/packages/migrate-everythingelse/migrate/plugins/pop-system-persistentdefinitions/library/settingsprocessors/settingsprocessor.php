<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

class PoP_PersistentDefinitionsSystem_Module_SettingsProcessor extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    // use PoP_PersistentDefinitionsSystem_Module_SettingsProcessor_Trait;
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE,
            )
        );
    }

    public function isForInternalUse()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE => true,
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
        );
    }
}

/**
 * Initialization
 */
new PoP_PersistentDefinitionsSystem_Module_SettingsProcessor();
