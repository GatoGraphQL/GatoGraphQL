<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents extends PoP_UserPreferences_SimpleProcessorAutomatedEmailsBase
{
    public function getRoute()
    {
        return POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY;
    }

    protected function getPreferenceonUsersValue()
    {
        return POP_USERPREFERENCES_EMAILDIGESTS_WEEKLYUPCOMINGEVENTS;
    }
    
    protected function getSubject()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('Upcoming events — %s', 'pop-commonautomatedemails'),
            date($cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')))
        );
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_AE_WeeklyUpcomingEvents();
