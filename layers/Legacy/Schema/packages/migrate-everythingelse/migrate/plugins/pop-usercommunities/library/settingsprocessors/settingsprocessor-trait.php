<?php

use PoP\ComponentModel\Checkpoints\CheckpointInterface;

trait PoP_UserCommunities_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
                POP_USERCOMMUNITIES_ROUTE_MEMBERS,
                POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS,
                POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
                POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
                POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP,
                POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES,
            )
        );
    }

    /**
     * @return array<string,CheckpointInterface[]>
     */
    public function getRouteCheckpoints(): array
    {
        return array(
            POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES => [$this->getUserLoggedInCheckpoint()],
            POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS => POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_STATIC,
            POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP => POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_EDITMEMBERSHIP_DATAFROMSERVER,
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_DATAFROMSERVER,
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP => true,
        );
    }
}
