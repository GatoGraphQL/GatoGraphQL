<?php
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

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
        );
    }
}
