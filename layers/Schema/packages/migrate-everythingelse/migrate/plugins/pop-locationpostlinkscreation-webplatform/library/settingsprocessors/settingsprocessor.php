<?php

class PoP_LocationPostLinksCreationWebPlatform_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_LocationPostLinksCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_LocationPostLinksCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreationWebPlatform_Module_SettingsProcessor();
