<?php

trait PoPWebPlatform_AddComments_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => true,
        );
    }
}
