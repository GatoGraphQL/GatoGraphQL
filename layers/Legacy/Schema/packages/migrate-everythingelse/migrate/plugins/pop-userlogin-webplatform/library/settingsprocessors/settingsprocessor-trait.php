<?php

trait PoPWebPlatform_UserLogin_Module_SettingsProcessor_Trait
{
    public function silentDocument()
    {
        return array(
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => true,
        );
    }
}
