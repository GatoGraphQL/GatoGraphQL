<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait AAL_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => true,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => true,
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            // POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => PoP_UserState_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERSTATE_CHECKPOINTCONFIGURATION_REQUIREUSERSTATE),
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => [$this->getUserLoggedInCheckpoint()],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => [$this->getUserLoggedInCheckpoint()],
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
