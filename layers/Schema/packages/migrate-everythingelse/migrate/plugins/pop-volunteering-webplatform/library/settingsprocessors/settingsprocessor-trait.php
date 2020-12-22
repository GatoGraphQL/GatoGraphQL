<?php

trait PoPWebPlatform_Volunteering_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_VOLUNTEERING_ROUTE_VOLUNTEER => true,
        );
    }
}
