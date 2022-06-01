<?php
trait PoP_ContentCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CONTENTCREATION_ROUTE_ADDCONTENT,
                POP_CONTENTCREATION_ROUTE_MYCONTENT,
                POP_CONTENTCREATION_ROUTE_FLAG,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_CONTENTCREATION_ROUTE_FLAG => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [$this->getUserLoggedInCheckpoint()],
        );
    }
}
