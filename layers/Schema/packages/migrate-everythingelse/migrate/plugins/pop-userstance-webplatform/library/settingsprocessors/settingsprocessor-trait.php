<?php

trait PoPWebPlatform_UserStance_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => true,
        );
    }
}
