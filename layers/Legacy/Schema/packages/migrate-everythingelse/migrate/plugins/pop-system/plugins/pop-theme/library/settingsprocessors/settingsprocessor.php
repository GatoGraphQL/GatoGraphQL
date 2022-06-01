<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

class PoPSystem_Theme_Module_SettingsProcessor extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    // use PoPSystem_Theme_Module_SettingsProcessor_Trait;
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME,
            )
        );
    }

    public function isForInternalUse()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME => true,
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
        );
    }
}

/**
 * Initialization
 */
new PoPSystem_Theme_Module_SettingsProcessor();
