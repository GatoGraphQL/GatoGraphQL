<?php

trait PoPWebPlatform_EventsCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_EVENTSCREATION_ROUTE_ADDEVENT => true,
        );
    }
}
