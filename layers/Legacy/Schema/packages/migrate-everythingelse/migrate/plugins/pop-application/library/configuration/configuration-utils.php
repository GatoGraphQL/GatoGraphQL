<?php

class PoP_Application_ConfigurationUtils
{
    public static function useUseravatar()
    {

        // If the plugin to create avatar is defined, then enable it
        // Allow user-avatar-popfork to override it, even if pop-useravatar is not activated
        return \PoP\Root\App::getHookManager()->applyFilters('PoP_Application_ConfigurationUtils:use-useravatar', defined('POP_AVATAR_INITIALIZED'));
    }
}
