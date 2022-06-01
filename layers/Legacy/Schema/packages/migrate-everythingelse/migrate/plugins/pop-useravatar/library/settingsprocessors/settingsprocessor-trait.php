<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoPUserAvatar_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERAVATAR_ROUTE_EDITAVATAR,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_USERAVATAR_ROUTE_EDITAVATAR => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
