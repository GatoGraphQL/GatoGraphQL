<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_EventLinksCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
                POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK => true,
        );
    }
}
