<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_CommonAutomatedEmails_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY,
                POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
            POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL => POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID,//PoPSystem_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPSYSTEM_CHECKPOINTCONFIGURATION_SYSTEMACCESSVALID),
        );
    }

    public function isForInternalUse()
    {
        return array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY => true,
            POP_COMMONAUTOMATEDEMAILS_ROUTE_SINGLEPOST_SPECIAL => true,
        );
    }
}
