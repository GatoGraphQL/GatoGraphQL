<?php

trait PoPWebPlatform_PostsCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_POSTSCREATION_ROUTE_ADDPOST => true,
        );
    }
}
