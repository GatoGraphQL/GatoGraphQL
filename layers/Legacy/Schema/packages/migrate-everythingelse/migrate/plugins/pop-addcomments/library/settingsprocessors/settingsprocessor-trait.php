<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_AddComments_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
        );
    }
}
