<?php
trait PoP_EventsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_EVENTSCREATION_ROUTE_MYEVENTS,
                POP_EVENTSCREATION_ROUTE_MYPASTEVENTS,
                POP_EVENTSCREATION_ROUTE_ADDEVENT,
                POP_EVENTSCREATION_ROUTE_EDITEVENT,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_EVENTSCREATION_ROUTE_ADDEVENT => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_EVENTSCREATION_ROUTE_MYEVENTS => [$this->getUserLoggedInCheckpoint()],
            POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => [$this->getUserLoggedInCheckpoint()],
            POP_EVENTSCREATION_ROUTE_EDITEVENT => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_EVENTSCREATION_ROUTE_EDITEVENT => true,
        );
    }
}
