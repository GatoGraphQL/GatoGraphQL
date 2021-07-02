<?php

class PoPWebPlatform_AddComments_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_AddComments_Module_SettingsProcessor_Trait, PoPWebPlatform_AddComments_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_AddComments_Module_SettingsProcessor();
