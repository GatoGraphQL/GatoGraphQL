<?php

class PoPWebPlatform_SocialNetwork_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_SocialNetwork_Module_SettingsProcessor_Trait;
    use PoPWebPlatform_SocialNetwork_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_SocialNetwork_Module_SettingsProcessor();
