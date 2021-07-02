<?php

abstract class PoP_WebPlatformEngine_ThemeModeBase extends \PoP\Theme\Themes\ThemeModeBase
{
    public function addJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false)
    {
        PoPWebPlatform_ModuleManager_Utils::addJsmethod($ret, $method, $group, $unshift);
    }
    public function removeJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN)
    {
        PoPWebPlatform_ModuleManager_Utils::removeJsmethod($ret, $method, $group);
    }
}
