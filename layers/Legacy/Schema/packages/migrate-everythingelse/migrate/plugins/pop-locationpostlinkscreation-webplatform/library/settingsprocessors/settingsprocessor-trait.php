<?php

trait PoPWebPlatform_LocationPostLinksCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => true,
        );
    }
}
