<?php

class PoPWebPlatform_PostsCreation_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_PostsCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_PostsCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_PostsCreation_Module_SettingsProcessor();
