<?php
namespace PoP\Theme\Themes;
use PoP\Hooks\Facades\HooksAPIFacade;

class ThemeManagerUtils
{
    public static function getThemeDir($themename)
    {
        return HooksAPIFacade::getInstance()->applyFilters('\PoP\Theme\Themes\ThemeManagerUtils:getThemeDir:'.$themename, '');
    }

    public static function getThememodeTemplatesDir($themename, $thememode)
    {
        return self::getThemeDir($themename).'/thememodes/'.$thememode.'/templates';
    }
}
