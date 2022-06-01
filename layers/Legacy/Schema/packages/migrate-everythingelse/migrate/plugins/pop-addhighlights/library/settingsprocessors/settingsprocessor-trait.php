<?php
trait PoP_AddHighlights_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT,
                POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT,
                POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
                POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
            POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
