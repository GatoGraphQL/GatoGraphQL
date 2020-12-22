<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class PoPTheme_Wassup_AE_WeeklyLatestContent extends PoP_UserPreferences_SimpleProcessorAutomatedEmailsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTCONTENT_WEEKLY;
    }

    protected function getPreferenceonUsersValue()
    {
        return POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYLATESTPOSTS;
    }
    
    protected function getSubject()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Latest content — %s', 'pop-commonautomatedemails'),
            date($cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_AE_WeeklyLatestContent();
