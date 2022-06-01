<?php
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

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_USERAVATAR_ROUTE_EDITAVATAR => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
