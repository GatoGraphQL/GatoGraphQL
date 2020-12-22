<?php

class PoPWebPlatform_UserLogin_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_UserLogin_Module_SettingsProcessor_Trait;
    use PoPWebPlatform_UserLogin_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_UserLogin_Module_SettingsProcessor();
