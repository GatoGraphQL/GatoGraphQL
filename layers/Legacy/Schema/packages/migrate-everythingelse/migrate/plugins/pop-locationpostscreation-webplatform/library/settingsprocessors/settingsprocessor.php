<?php

class PoP_LocationPostsCreationWebPlatform_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_LocationPostsCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_LocationPostsCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoP_LocationPostsCreationWebPlatform_Module_SettingsProcessor();
