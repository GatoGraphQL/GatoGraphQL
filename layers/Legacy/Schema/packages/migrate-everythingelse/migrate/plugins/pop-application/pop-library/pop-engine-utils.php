<?php

define('POP_HOOK_POPMANAGERUTILS_EMBEDURL', 'PoP_Application_Engine_Utils:getEmbedUrl');
define('POP_HOOK_POPMANAGERUTILS_PRINTURL', 'PoP_Application_Engine_Utils:getPrintUrl');

class PoP_Application_Engine_Utils
{
    public static function getEmbedUrl($url)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(POP_HOOK_POPMANAGERUTILS_EMBEDURL, $url);
    }

    public static function getPrintUrl($url)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(POP_HOOK_POPMANAGERUTILS_PRINTURL, $url);
    }
}
