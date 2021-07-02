<?php

class PoPWebPlatform_EventsCreation_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_EventsCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_EventsCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_EventsCreation_Module_SettingsProcessor();
