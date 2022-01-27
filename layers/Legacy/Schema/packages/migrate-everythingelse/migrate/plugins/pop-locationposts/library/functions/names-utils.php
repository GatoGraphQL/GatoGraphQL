<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_LocationPosts_PostNameUtils
{
    public static function getNameUc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_LocationPosts_PostNameUtils:name:uc',
            TranslationAPIFacade::getInstance()->__('Location post', 'pop-locationposts')
        );
    }
    public static function getNamesUc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_LocationPosts_PostNameUtils:names:uc',
            TranslationAPIFacade::getInstance()->__('Location posts', 'pop-locationposts')
        );
    }
    public static function getNameLc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_LocationPosts_PostNameUtils:name:lc',
            TranslationAPIFacade::getInstance()->__('location post', 'pop-locationposts')
        );
    }
    public static function getNamesLc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_LocationPosts_PostNameUtils:names:lc',
            TranslationAPIFacade::getInstance()->__('location posts', 'pop-locationposts')
        );
    }
}
