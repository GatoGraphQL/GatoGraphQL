<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_LocationPostLinksCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
                POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => true,
        );
    }
}
