<?php

class PoPWebPlatform_Volunteering_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_Volunteering_Module_SettingsProcessor_Trait, PoPWebPlatform_Volunteering_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_Volunteering_Module_SettingsProcessor();
