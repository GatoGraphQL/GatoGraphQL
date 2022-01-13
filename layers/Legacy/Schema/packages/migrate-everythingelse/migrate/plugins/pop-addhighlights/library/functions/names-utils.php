<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_PostNameUtils
{
    public static function getNameUc()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:name:uc',
            TranslationAPIFacade::getInstance()->__('Highlight', 'pop-addhighlights')
        );
    }
    public static function getNamesUc()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:names:uc',
            TranslationAPIFacade::getInstance()->__('Highlights', 'pop-addhighlights')
        );
    }
    public static function getNameLc()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:name:lc',
            TranslationAPIFacade::getInstance()->__('highlight', 'pop-addhighlights')
        );
    }
    public static function getNamesLc()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:names:lc',
            TranslationAPIFacade::getInstance()->__('highlights', 'pop-addhighlights')
        );
    }
}
