<?php

class PoPWebPlatform_UserStance_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_UserStance_Module_SettingsProcessor_Trait, PoPWebPlatform_UserStance_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_UserStance_Module_SettingsProcessor();
