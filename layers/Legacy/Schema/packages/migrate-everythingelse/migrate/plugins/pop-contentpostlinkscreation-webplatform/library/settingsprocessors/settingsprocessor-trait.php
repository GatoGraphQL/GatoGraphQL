<?php

trait PoPWebPlatform_ContentPostLinksCreation_Module_SettingsProcessor_Trait
{
    public function isMultipleopen()
    {
        return array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK => true,
        );
    }
}
