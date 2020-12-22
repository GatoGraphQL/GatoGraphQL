<?php

class PoPWebPlatform_EventLinksCreation_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_EventLinksCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_EventLinksCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_EventLinksCreation_Module_SettingsProcessor();
