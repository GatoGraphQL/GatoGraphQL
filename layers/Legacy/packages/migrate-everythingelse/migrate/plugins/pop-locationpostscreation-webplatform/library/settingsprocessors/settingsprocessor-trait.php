<?php

trait PoPWebPlatform_LocationPostsCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => true,
        );
    }
}
