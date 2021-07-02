<?php

class PoP_LocationPostsWebPlatform_Module_SettingsProcessor extends PoP_WebPlatformEngine_Module_SettingsProcessorBase
{
    use PoP_LocationPosts_Module_SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoP_LocationPostsWebPlatform_Module_SettingsProcessor();
