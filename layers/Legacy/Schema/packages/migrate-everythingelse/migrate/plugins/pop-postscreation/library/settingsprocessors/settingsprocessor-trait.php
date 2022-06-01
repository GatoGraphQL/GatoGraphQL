<?php
trait PoP_PostsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTSCREATION_ROUTE_ADDPOST,
                POP_POSTSCREATION_ROUTE_EDITPOST,
                POP_POSTSCREATION_ROUTE_MYPOSTS,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_POSTSCREATION_ROUTE_EDITPOST => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_POSTSCREATION_ROUTE_ADDPOST => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_POSTSCREATION_ROUTE_EDITPOST => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
            POP_POSTSCREATION_ROUTE_MYPOSTS => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
