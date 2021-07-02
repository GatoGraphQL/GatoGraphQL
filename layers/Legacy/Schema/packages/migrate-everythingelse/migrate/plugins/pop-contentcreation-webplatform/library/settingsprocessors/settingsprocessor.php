<?php

class PoPWebPlatform_ContentCreation_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_ContentCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_ContentCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_ContentCreation_Module_SettingsProcessor();
