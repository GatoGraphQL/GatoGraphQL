<?php

class PoPWebPlatform_ContentPostLinksCreation_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_ContentPostLinksCreation_Module_SettingsProcessor_Trait, PoPWebPlatform_ContentPostLinksCreation_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_ContentPostLinksCreation_Module_SettingsProcessor();
