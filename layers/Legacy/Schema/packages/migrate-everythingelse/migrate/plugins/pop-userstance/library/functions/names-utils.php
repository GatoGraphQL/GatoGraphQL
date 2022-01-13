<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserStance_PostNameUtils
{
    public static function getNameUc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStance_PostNameUtils:name:uc',
            TranslationAPIFacade::getInstance()->__('Stance', 'pop-userstance')
        );
    }
    public static function getNamesUc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStance_PostNameUtils:names:uc',
            TranslationAPIFacade::getInstance()->__('Stances', 'pop-userstance')
        );
    }
    public static function getNameLc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStance_PostNameUtils:name:lc',
            TranslationAPIFacade::getInstance()->__('stance', 'pop-userstance')
        );
    }
    public static function getNamesLc()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStance_PostNameUtils:names:lc',
            TranslationAPIFacade::getInstance()->__('stances', 'pop-userstance')
        );
    }
    
    public static function getTermNames()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_UserStance_PostNameUtils:term-names',
            array(
                POP_USERSTANCE_TERM_STANCE_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance'),
                POP_USERSTANCE_TERM_STANCE_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance'),
                POP_USERSTANCE_TERM_STANCE_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance'),
            )
        );
    }
}
