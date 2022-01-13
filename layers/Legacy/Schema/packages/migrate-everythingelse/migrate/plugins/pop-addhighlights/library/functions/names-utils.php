<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_PostNameUtils
{
    public static function getNameUc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:name:uc',
            TranslationAPIFacade::getInstance()->__('Highlight', 'pop-addhighlights')
        );
    }
    public static function getNamesUc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:names:uc',
            TranslationAPIFacade::getInstance()->__('Highlights', 'pop-addhighlights')
        );
    }
    public static function getNameLc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:name:lc',
            TranslationAPIFacade::getInstance()->__('highlight', 'pop-addhighlights')
        );
    }
    public static function getNamesLc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AddHighlights_PostNameUtils:names:lc',
            TranslationAPIFacade::getInstance()->__('highlights', 'pop-addhighlights')
        );
    }
}
