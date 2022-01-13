<?php
namespace PoP\Theme\Themes;

class ThemeManagerUtils
{
    public static function getThemeDir($themename)
    {
        return \PoP\Root\App::applyFilters('\PoP\Theme\Themes\ThemeManagerUtils:getThemeDir:'.$themename, '');
    }

    public static function getThememodeTemplatesDir($themename, $thememode)
    {
        return self::getThemeDir($themename).'/thememodes/'.$thememode.'/templates';
    }
}
