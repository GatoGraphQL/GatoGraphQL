<?php

trait PoPWebPlatform_EventLinksCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => true,
        );
    }
}
