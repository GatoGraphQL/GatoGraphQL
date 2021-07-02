<?php

trait PoPWebPlatform_ContentCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_CONTENTCREATION_ROUTE_FLAG => true,
        );
    }
}
