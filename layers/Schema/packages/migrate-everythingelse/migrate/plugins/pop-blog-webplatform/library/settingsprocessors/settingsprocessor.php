<?php

class PoPWebPlatform_Blog_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_Blog_Module_SettingsProcessor_Trait;
    use PoPWebPlatform_Blog_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_Blog_Module_SettingsProcessor();
