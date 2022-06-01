<?php
trait PoP_LocationPostsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
                POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
                POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [$this->getUserLoggedInCheckpoint()],
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => true,
        );
    }
}
