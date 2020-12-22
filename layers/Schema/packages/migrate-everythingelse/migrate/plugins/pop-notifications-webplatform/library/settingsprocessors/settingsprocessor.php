<?php

class PoPWebPlatform_AAL_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use AAL_Module_SettingsProcessor_Trait;
    use PoPWebPlatform_AAL_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPWebPlatform_AAL_Module_SettingsProcessor();
