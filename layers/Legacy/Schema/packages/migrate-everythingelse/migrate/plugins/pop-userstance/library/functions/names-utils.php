<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserStance_PostNameUtils
{
    public static function getNameUc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_UserStance_PostNameUtils:name:uc',
            TranslationAPIFacade::getInstance()->__('Stance', 'pop-userstance')
        );
    }
    public static function getNamesUc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_UserStance_PostNameUtils:names:uc',
            TranslationAPIFacade::getInstance()->__('Stances', 'pop-userstance')
        );
    }
    public static function getNameLc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_UserStance_PostNameUtils:name:lc',
            TranslationAPIFacade::getInstance()->__('stance', 'pop-userstance')
        );
    }
    public static function getNamesLc()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_UserStance_PostNameUtils:names:lc',
            TranslationAPIFacade::getInstance()->__('stances', 'pop-userstance')
        );
    }
    
    public static function getTermNames()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_UserStance_PostNameUtils:term-names',
            array(
                POP_USERSTANCE_TERM_STANCE_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance'),
                POP_USERSTANCE_TERM_STANCE_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance'),
                POP_USERSTANCE_TERM_STANCE_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance'),
            )
        );
    }
}
